<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionsChart
{
    protected LarapexChart $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(?int $year = null): LarapexChart
    {
        $year = $year ?? Carbon::now()->year;

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $incomeRows = Transaction::selectRaw('MONTH(`date`) as month, SUM(amount) as total')
            ->where('type', 'income')
            ->whereYear('date', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $expenseRows = Transaction::selectRaw('MONTH(`date`) as month, SUM(amount) as total')
            ->where('type', 'expenditure')
            ->whereYear('date', $year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $incomeData = [];
        $expenseData = [];

        for ($m = 1; $m <= 12; $m++) {
            $incomeData[] = isset($incomeRows[$m]) ? (float) $incomeRows[$m] : 0;
            $expenseData[] = isset($expenseRows[$m]) ? (float) $expenseRows[$m] : 0;
        }

        return $this->chart->lineChart()
            ->setTitle('Grafik Transaksi Tahun  ' . $year)
            ->setXAxis($months)
            ->setDataset([
                [
                    'name' => 'Pemasukan',
                    'data' => $incomeData,
                ],
                [
                    'name' => 'Pengeluaran', 
                    'data' => $expenseData,
                ],
            ])
            ->setColors(['#3B82F6', '#EF4444'])
            ->setHeight(450);
    }
}