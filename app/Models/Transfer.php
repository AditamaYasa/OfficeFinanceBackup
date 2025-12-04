<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    
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
    ];
}