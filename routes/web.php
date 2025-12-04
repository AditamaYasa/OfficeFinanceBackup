<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\CategoryTransactionController;

Route::get('/', function () {
    return view('splash');
})->name('splash');

Route::prefix('admin')->name('admin.')->group(base_path('routes/auth/admin.php'));

require base_path('routes/auth/user.php');

Route::middleware(['auth:web,admin'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::get('/transactions/{id}/download', [TransactionController::class, 'downloadAttachment'])
    ->name('transactions.download');

    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/api/category/{type}', [CategoryController::class, 'getByType'])->name('api.category.byType');
    
    Route::get('/category/income/{slug}', [CategoryTransactionController::class, 'income'])->name('category.income');
    Route::get('/category/expenditure/{slug}', [CategoryTransactionController::class, 'expenditure'])->name('category.expenditure');

    Route::get('/categories/income/{slug}/export', [CategoryTransactionController::class, 'exportIncome'])
        ->name('categories.income.export');
    Route::get('/categories/expenditure/{slug}/export', [CategoryTransactionController::class, 'exportExpenditure'])
        ->name('categories.expenditure.export');

    Route::get('/report', [ReportsController::class, 'index'])->name('report.index');
    Route::get('/api/chart-data', [ReportsController::class, 'getChartData'])->name('chart.data');
    Route::get('/reports/export', [ReportsController::class, 'export'])->name('reports.export');

    Route::middleware('admin')->group(function () {
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::put('/transactions/{id}', [TransactionController::class, 'update']);
        Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
        Route::post('/transactions/bulk-delete', [TransactionController::class, 'bulkDelete']);

        Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
        Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    });
});
