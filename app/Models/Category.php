<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';

    protected $fillable = [
        'category_name',
        'type',
        'slug',
    ];
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpenditure($query)
    {
        return $query->where('type', 'expenditure');
    }
}
