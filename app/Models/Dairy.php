<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dairy extends Model
{
    use HasFactory;
     use SoftDeletes;

     protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'name',
        'location',
        'admin_userid',
        'phone',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_userid');
    }

    public function fundAllocations()
    {
        return $this->hasMany(FundAllocation::class, 'dairy_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'dairy_id');
    }


}
