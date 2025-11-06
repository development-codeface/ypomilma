<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Assets;
use App\Models\Product;

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

    public function product(){
        return $this->hasOneThrough(
            Product::class,
            Assets::class,
            'id', // Foreign key on Asset table
            'id', // Foreign key on Product table
            'asset_id', // Local key on AggencyBill table
            'product_id' // Local key on Asset table
        );
    }
}
