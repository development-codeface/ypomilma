<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'product_id',
        'expense_item',
        'rate',
        'quantity',
        'dairy_id',
        'is_head_office',
        'fund_id',
        'expensecategory_id',
        'amount',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expensecategory_id');
    }

    public function dairy()
    {
        return $this->belongsTo(Dairy::class, 'dairy_id');
    }

    public function getItemNameAttribute()
    {
        // 1️⃣ Try to get from products
        $product = \App\Models\Product::where('item_code', $this->expense_item)->first();
        if ($product) {
            return $product->productname;
        }

        // 2️⃣ Else try from expense_items
        $item = \App\Models\ExpenseItem::where('item_code', $this->expense_item)->first();
        if ($item) {
            return $item->item_name;
        }

        // 3️⃣ Fallback
        return $this->expense_item; // show code if name not found
    }
}
