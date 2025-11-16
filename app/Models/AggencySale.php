<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AggencySale extends Model
{
    //fields that can be mass assigned
    protected $fillable = [
        'id',
        'agency_id',
        'invoice_id',
        'total_amount',
        'dairy_id'
    ];


}
