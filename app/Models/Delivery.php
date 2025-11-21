<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'delivery_no', 'invoice_id', 'created_by', 'delivery_date', 'notes'
    ];

    public function items()
    {
        return $this->hasMany(DeliveryItem::class);
    }
}
