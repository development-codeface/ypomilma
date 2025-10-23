<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public $incrementing = false; // because ID is string (custom)
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'dairy_id', 'vendor_id', 'discount', 'total_amount', 'status'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    public function dairy()
    {
        return $this->belongsTo(Dairy::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         if (!$model->id) {
    //             $lastId = static::orderBy('id', 'desc')->first()?->id;
    //             $num = $lastId ? (int) substr($lastId, 3) + 1 : 1;
    //             $model->id = 'INV' . str_pad($num, 5, '0', STR_PAD_LEFT);
    //         }
    //     });
    // }
}
