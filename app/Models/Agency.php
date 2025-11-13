<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model
{
      use HasFactory,SoftDeletes;
    //fields that can be mass assigned
    protected $fillable = [
        'id',
        'name',
        'email',
        'contact_no',
        'address',
        'dairy_id',
    ];
}
