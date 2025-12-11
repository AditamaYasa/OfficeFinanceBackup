<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with([
                'sidebarIncomeCategories' => Category::income()->get(),
                'sidebarExpenditureCategories' => Category::expenditure()->get()
            ]);
        });

        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}