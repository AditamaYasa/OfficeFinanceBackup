<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Transaction;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $selectedMonthName = $monthNames[$selectedMonth] ?? '';

        $availableYears = Transaction::selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $query = Transaction::query()
            ->whereMonth('date', $selectedMonth)
            ->whereYear('date', $selectedYear);

        $income = (clone $query)->where('type', 'income')->sum('amount');
        $expenditure = (clone $query)->where('type', 'expenditure')->sum('amount');

        $incomePerCategory = Transaction::join('category', 'transactions.category_id', '=', 'category.id')
            ->where('transactions.type', 'income')
            ->whereMonth('transactions.date', $selectedMonth)
            ->whereYear('transactions.date', $selectedYear)
            ->selectRaw('category.category_name, SUM(transactions.amount) as total')
            ->groupBy('category.id', 'category.category_name')
            ->get();

        $expenditurePerCategory = Transaction::join('category', 'transactions.category_id', '=', 'category.id')
            ->where('transactions.type', 'expenditure')
            ->whereMonth('transactions.date', $selectedMonth)
            ->whereYear('transactions.date', $selectedYear)
            ->selectRaw('category.category_name, SUM(transactions.amount) as total')
            ->groupBy('category.id', 'category.category_name')
            ->get();

        $incomeLabels = $incomePerCategory->pluck('category_name')->toArray();
        $incomeValues = $incomePerCategory->pluck('total')->map(fn($v) => (float)$v)->toArray();

        $expenditureLabels = $expenditurePerCategory->pluck('category_name')->toArray();
        $expenditureValues = $expenditurePerCategory->pluck('total')->map(fn($v) => (float)$v)->toArray();

        $incomeColors = ['#16A34A','#22C55E','#4ADE80','#86EFAC','#BBF7D0','#DCFCE7',
                        '#15803D','#166534','#14532D','#047857','#065F46','#064E3B'];
        
        $expenditureColors = ['#DC2626','#EF4444','#F87171','#FCA5A5','#FECACA','#FEE2E2',
                             '#B91C1C','#991B1B','#7F1D1D','#9F1239','#BE123C','#E11D48'];

        $incomeChart = (new LarapexChart)->pieChart()
            ->setDataset($incomeValues)
            ->setLabels($incomeLabels)
            ->setHeight(300)
            ->setColors($incomeColors);

        $expenditureChart = (new LarapexChart)->pieChart()
            ->setDataset($expenditureValues)
            ->setLabels($expenditureLabels)
            ->setHeight(300)
            ->setColors($expenditureColors);

        $incomePercentages = [];
        if ($income > 0) {
            foreach ($incomePerCategory as $index => $item) {
                $incomePercentages[] = [
                    'category' => $item->category_name,
                    'amount' => $item->total,
                    'percentage' => round(($item->total / $income) * 100, 1),
                    'color' => $incomeColors[$index % count($incomeColors)]
                ];
            }
        }

        $expenditurePercentages = [];
        if ($expenditure > 0) {
            foreach ($expenditurePerCategory as $index => $item) {
                $expenditurePercentages[] = [
                    'category' => $item->category_name,
                    'amount' => $item->total,
                    'percentage' => round(($item->total / $expenditure) * 100, 1),
                    'color' => $expenditureColors[$index % count($expenditureColors)]
                ];
            }
        }

        return view('report', compact(
            'income', 'expenditure',
            'incomeChart', 'expenditureChart',
            'incomePercentages', 'expenditurePercentages',
            'selectedMonthName', 'selectedYear','availableYears'
        ));
    }
    
    public function export(Request $request)
{
    $selectedYear = $request->input('year', now()->year);
    
    $fileName = 'Laporan_Transaksi_' . $selectedYear . '.xlsx';
    
    return Excel::download(new TransactionsExport($selectedYear), $fileName);
}
}