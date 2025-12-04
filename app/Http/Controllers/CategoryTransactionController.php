<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoryTransactionsExport;

class CategoryTransactionController extends Controller
{
    public function income(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $year = $request->query('year', now()->year);
        $month = $request->query('month', now()->month);

        $transactions = Transaction::where('category_id', $category->id)
            ->where('type', 'income')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->simplePaginate(4);

        $recentTransactions = Transaction::where('category_id', $category->id)
            ->where('type', 'income')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();

        $monthlyData = Transaction::where('category_id', $category->id)
            ->where('type', 'income')
            ->whereYear('date', $year)
            ->selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $labels = [];
        $values = [];
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $monthNames[$i - 1];
            $monthData = $monthlyData->firstWhere('month', $i);
            $values[] = $monthData ? (float) $monthData->total : 0;
        }

        $chart = (new LarapexChart)->lineChart()
            ->setTitle('')
            ->setLabels($labels)
            ->setDataset([
                [
                    'name' => 'Pemasukan',
                    'data' => $values
                ]
            ])
            ->setHeight(250)
            ->setColors(['#0B3B9F']);

        $availableYears = Transaction::where('category_id', $category->id)
            ->where('type', 'income')
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return view('transactions.income', compact('category', 'chart', 'recentTransactions', 'transactions', 'year', 'month', 'availableYears', 'monthNames'));
    }

    public function expenditure(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $year = $request->query('year', now()->year);
        $month = $request->query('month', now()->month);

        $transactions = Transaction::where('category_id', $category->id)
            ->where('type', 'expenditure')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->simplePaginate(4);

        $recentTransactions = Transaction::where('category_id', $category->id)
            ->where('type', 'expenditure')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();

        $monthlyData = Transaction::where('category_id', $category->id)
            ->where('type', 'expenditure')
            ->whereYear('date', $year)
            ->selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $labels = [];
        $values = [];
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $monthNames[$i - 1];
            $monthData = $monthlyData->firstWhere('month', $i);
            $values[] = $monthData ? (float) $monthData->total : 0;
        }

        $chart = (new LarapexChart)->lineChart()
            ->setTitle('')
            ->setLabels($labels)
            ->setDataset([
                [
                    'name' => 'Pengeluaran',
                    'data' => $values
                ]
            ])
            ->setHeight(250)
            ->setColors(['#F20E0F']);

        $availableYears = Transaction::where('category_id', $category->id)
            ->where('type', 'expenditure')
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return view('transactions.expenditure', compact('category', 'chart', 'recentTransactions', 'transactions', 'year', 'month', 'availableYears', 'monthNames'));
    }
    
    public function exportIncome(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $year = $request->query('year', now()->year);
        $categoryName = $category->category_name;

        return Excel::download(
            new CategoryTransactionsExport($category->id, 'income', $year),
            'laporan_' . $categoryName . '_' . $year . '.xlsx'
        );
    }

    public function exportExpenditure(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $year = $request->query('year', now()->year);
        $categoryName = $category->category_name;

        return Excel::download(
            new CategoryTransactionsExport($category->id, 'expenditure', $year),
            'laporan_' . $categoryName . '_' . $year . '.xlsx'
        );
    }
}
