<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountHistory extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'amount', 'balance'];

    protected $casts = [
        'amount' => 'double',
        'balance' => 'double',
    ];
}