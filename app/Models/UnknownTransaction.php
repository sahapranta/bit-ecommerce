<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnknownTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'txid',
        'address',
        'amount',
        'confirmations',
    ];

    protected $casts = [
        'confirmations' => 'integer',
    ];
}
