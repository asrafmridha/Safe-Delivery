<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'designation',
        'address',
        'branch_id',
        'salary',
        'image',
        'status',
        'created_admin_id',
        'updated_admin_id',
    ];


    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id')->withDefault(['name' => 'Branch Name']);
    }

    public function staff_payments()
    {
        return $this->hasMany(StaffPayment::class, 'staff_id');
    }
}
