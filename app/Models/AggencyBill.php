<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AggencyBill extends Model
{
    //fields that can be mass assigned
      protected $fillable = [
        'id',
        'aggency_sale_id',
        'asset_id',
        'price',
        'discount',
        'quantity',
        'gst_percent',
        'gst_amount',
        'tax_type',
        'total',
    ];
}
