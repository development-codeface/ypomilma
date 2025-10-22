<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory;
     use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status', // 0 for inactive, 1 for active
    ];

    protected $casts = [
        'status' => 'boolean',
         'deleted_at' => 'datetime',
    ];
}
