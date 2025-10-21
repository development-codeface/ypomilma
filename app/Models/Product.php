<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'productname',
        'img',
        'description',
        'vendor_id',
        'brand',
        'model',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
