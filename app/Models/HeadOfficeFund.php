<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeadOfficeFund extends Model
{
    use HasFactory;

    protected $table = 'head_office_fund';

    protected $fillable = [
        'financial_year',
        'amount',
    ];
}
