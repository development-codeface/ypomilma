<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'dairy_id',
        'amount',
        'allocation_date',
        'financial_year',
        'remarks',
        'status',
    ];

    // Define relationship with Dairy model
    public function dairy()
    {
        return $this->belongsTo(Dairy::class);
    }
}
