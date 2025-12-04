<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Routing\Controller;

class BaseController extends Controller 
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $incomeCategories = Category::income()->get();
            $expenditureCategories = Category::expenditure()->get();
            
            View::share('sidebarIncomeCategories', $incomeCategories);
            View::share('sidebarExpenditureCategories', $expenditureCategories);
            
            return $next($request);
        });
    }
}