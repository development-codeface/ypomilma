<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
     use SoftDeletes;

    protected $fillable = [
        'productname',
        'img',
        'description',
        'vendor_id',
        'brand',
        'model',
        'item_code',
        'category_id',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
}
