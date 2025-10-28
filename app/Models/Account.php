<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'dairy_id',
        'financial_year',
        'opening_balance',
        'main_balance',
    ];

    /**
     * Each account belongs to one dairy.
     */
    public function dairy()
    {
        return $this->belongsTo(Dairy::class);
    }

 
    /**
     * Update account balance dynamically.
     * Adds credit or subtracts debit.
     */
    public function applyTransaction(string $type, float $amount)
    {
        if ($type === 'credit') {
            $this->main_balance += $amount;
        } elseif ($type === 'debit') {
            $this->main_balance -= $amount;
        }
        $this->save();
    }

    /**
     * Get the current balance (opening + all changes)
     */
    public function getCurrentBalanceAttribute()
    {
        return $this->opening_balance + $this->main_balance;
    }
}
