<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'type',
        'category_id',
        'amount',
        'date_entry',
        'description',
        'date_factur',
        'no_factur',
        'date',
        'attachment',
        'payment',
        'date_maturity',
    ];

    protected $casts = [
        'date' => 'date',
        'date_entry' => 'date',
        'date_factur' => 'date',
        'date_maturity' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}