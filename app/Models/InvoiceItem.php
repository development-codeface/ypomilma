<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id', 'product_name', 'quantity', 'unit_price','product_id',
        'gst_percent', 'gst_amount', 'discount', 'taxable_value', 'total','tax_type'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function product() 
    { 
        return $this->belongsTo(Product::class); 
    }
}
