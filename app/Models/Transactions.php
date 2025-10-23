<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
     protected $fillable = [
        'id', 'dairy_id', 'fund_allocation_id', 'expense_category_id', 'type', 'amount','reference_no','description','status','transaction_date'
    ];
}
