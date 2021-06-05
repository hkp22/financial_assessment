<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = ['balance'];

    protected $casts = [
        'balance' => 'double',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function history()
    {
        return $this->hasMany(AccountHistory::class, 'bank_account_id');
    }

    public function withdrawAmount(float $amount, string $message)
    {
        $this->decrement('balance', $amount);

        $this->history()->create([
            'description' => $message,
            'amount' => $amount,
            'balance' => $this->balance
        ]);
    }

    public function addAmount(float $amount, string $message)
    {
        $this->increment('balance', $amount);

        $this->history()->create([
            'description' => $message,
            'amount' => $amount,
            'balance' => $this->balance
        ]);
    }
}