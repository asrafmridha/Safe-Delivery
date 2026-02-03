<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'salary_amount',
        'paid_amount',
        'payment_month',
        'payment_date',
        'created_admin_id'
    ];


    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id')->with(['branch'])->withDefault(['name' => 'Staff Name']);
    }
}
