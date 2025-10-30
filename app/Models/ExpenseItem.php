<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_name',
        'item_code',
        'description',
        'category_id',
   
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    // Optional: Accessor for display name
    public function getDisplayNameAttribute()
    {
        return "{$this->item_code} - {$this->item_name}";
    }
}
