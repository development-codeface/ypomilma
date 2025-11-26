<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AggencyBillUnit extends Model
{
    protected $fillable = [
        'aggency_bill_id',
        'serial_no',
        'brand',
        'model',
        'warranty',
        'description',
    ];

    public function bill()
    {
        return $this->belongsTo(AggencyBill::class, 'aggency_bill_id');
    }
}
