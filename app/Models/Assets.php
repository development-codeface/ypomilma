<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{
    protected $fillable = [
        'id',
        'dairy_id',
        'product_id',
        'purchase_value',
        'purchase_date',
        'sold_price',
        'discount',
        'invoice_refno',
        'status',
    ];
}
