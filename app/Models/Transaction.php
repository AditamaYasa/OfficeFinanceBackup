<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'type',
        'category_id',
        'payment',
        'amount',
        'date',
        'description',
        'date_factur',
        'no_factur',
        'attachment',
        'date_entry',
        'date_maturity',
    ];

    protected $casts = [
        'date' => 'date',
        'date_factur' => 'date',
        'date_entry' => 'datetime',
        'date_maturity' => 'date',
        'amount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpenditure($query)
    {
        return $query->where('type', 'expenditure');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeGiroPending($query)
    {
        return $query->where('payment', 'giro')
                    ->where(function($q) {
                        $q->whereNull('date_maturity')
                          ->orWhere('date_maturity', '>', Carbon::now());
                    });
    }

    public function scopeGiroCleared($query)
    {
        return $query->where('payment', 'giro')
                    ->where('date_maturity', '<=', Carbon::now());
    }

    public function isGiroCleared()
    {
        if ($this->payment !== 'giro' || !$this->date_maturity) {
            return false;
        }
        return Carbon::parse($this->date_maturity)->lte(Carbon::now());
    }
}