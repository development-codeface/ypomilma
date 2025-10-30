<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceItem;

class Assets extends Model
{
    protected $fillable = [
        'id',
        'invoice_items_id',
        'dairy_id',
        'product_id',
        'purchase_value',
        'purchase_date',
        'sold_price',
        'discount',
        'quantity',
        'invoice_refno',
        'status',
    ];

    public function dairy()
    {
        return $this->belongsTo(Dairy::class, 'dairy_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function invoiceItem()
    {
        return $this->belongsTo(InvoiceItem::class, 'invoice_items_id');
    }
}
