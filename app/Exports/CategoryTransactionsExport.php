<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class CategoryTransactionsExport implements WithMultipleSheets
{
    protected $categoryId;
    protected $type;
    protected $year;

    public function __construct(int $categoryId, string $type, ?int $year = null)
    {
        $this->categoryId = $categoryId;
        $this->type = $type;
        $this->year = $year ?? now()->year;
    }

    public function sheets(): array
    {
        $sheets = [];
        
        // Sheet pertama: Seluruh data setahun
        $sheets[] = new CategoryYearSheet($this->categoryId, $this->type, $this->year);
        
        // 12 Sheet untuk setiap bulan
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        for ($month = 1; $month <= 12; $month++) {
            $sheets[] = new CategoryMonthSheet(
                $this->categoryId, 
                $this->type, 
                $month, 
                $this->year, 
                $monthNames[$month]
            );
        }
        
        return $sheets;
    }
}

// Sheet untuk data setahun per kategori
class CategoryYearSheet implements FromCollection, WithHeadings, WithColumnWidths, WithStyles, WithEvents, \Maatwebsite\Excel\Concerns\WithTitle
{
    protected $transactions;
    protected $categoryId;
    protected $type;
    protected $year;

    public function __construct($categoryId, $type, $year)
    {
        $this->categoryId = $categoryId;
        $this->type = $type;
        $this->year = $year;
        
        $this->transactions = Transaction::with('category')
            ->where('category_id', $categoryId)
            ->where('type', $type)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get();
    }

    public function title(): string
    {
        return 'Laporan Tahun ' . $this->year;
    }

    public function collection(): Collection
    {
        return $this->transactions->map(function ($t, $index) {
            $attachmentInfo = $this->getAttachmentInfo($t);
            $paymentMethod = $this->getPaymentMethod($t);

            return [
                $index + 1,
                optional($t->date)->format('Y-m-d'),
                $t->type,
                optional($t->category)->category_name,
                $t->description,
                (float) $t->amount,
                $paymentMethod,
                $t->no_factur,
                optional($t->date_factur)->format('Y-m-d'),
                $attachmentInfo,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No', 'Tanggal', 'Jenis', 'Kategori', 'Keterangan',
            'Nominal', 'Metode Pembayaran', 'No Faktur', 'Tanggal Faktur', 'Lampiran'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5, 'B' => 12, 'C' => 12, 'D' => 20, 'E' => 30,
            'F' => 15, 'G' => 18, 'H' => 15, 'I' => 12, 'J' => 25,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE6E6FA']
                ]
            ],
            'J:J' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'wrapText' => true,
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $this->applySheetFormatting($event, $this->transactions);
            },
        ];
    }

    private function getAttachmentInfo($transaction)
    {
        if (!$transaction->attachment) {
            return "➖ Tidak ada lampiran";
        }

        $filePath = storage_path('app/public/' . $transaction->attachment);
        if (!file_exists($filePath)) {
            return "❌ File tidak ditemukan";
        }

        $fileName = basename($transaction->attachment);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
            return "🖼️ " . $fileName;
        } elseif ($fileExtension === 'pdf') {
            return "📄 " . $fileName;
        } else {
            return "📎 " . $fileName;
        }
    }

    private function getPaymentMethod($transaction)
    {
        return match($transaction->payment) {
            'cash' => 'Tunai',
            'transfer' => 'Transfer',
            'giro' => 'Giro',
            default => ucfirst($transaction->payment ?? '-')
        };
    }

    private function applySheetFormatting($event, $transactions)
    {
        $sheet = $event->sheet->getDelegate();
        $row = 2;

        foreach ($transactions as $transaction) {
            if ($transaction->attachment) {
                $attachmentPath = storage_path('app/public/' . $transaction->attachment);
                if (file_exists($attachmentPath)) {
                    $fileUrl = asset('storage/' . $transaction->attachment);
                    $sheet->getCell('J' . $row)->getHyperlink()->setUrl($fileUrl);
                    $sheet->getStyle('J' . $row)->getFont()
                        ->setUnderline(true)
                        ->getColor()->setARGB('FF0000FF');

                    $fileName = basename($transaction->attachment);
                    $richText = new RichText();
                    $richText->createText('Klik untuk membuka: ' . $fileName);
                    $sheet->getComment('J' . $row)
                        ->setAuthor('System')
                        ->setText($richText);
                } else {
                    $sheet->getStyle('J' . $row)->getFont()
                        ->getColor()->setARGB('FFFF0000');
                }
            }
            $row++;
        }

        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:J1');
        $sheet->getDefaultRowDimension()->setRowHeight(18);

        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:J' . $highestRow)->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }
}

// Sheet untuk data per bulan per kategori
class CategoryMonthSheet extends CategoryYearSheet
{
    protected $month;
    protected $monthName;

    public function __construct($categoryId, $type, $month, $year, $monthName)
    {
        $this->categoryId = $categoryId;
        $this->type = $type;
        $this->month = $month;
        $this->year = $year;
        $this->monthName = $monthName;
        
        $this->transactions = Transaction::with('category')
            ->where('category_id', $categoryId)
            ->where('type', $type)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date')
            ->get();
    }

    public function title(): string
    {
        return $this->monthName . ' ' . $this->year;
    }
}