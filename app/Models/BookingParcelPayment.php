<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingParcelPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_no',
        'booking_ids',
        'receive_booking_ids',
        'payment_parcel',
        'receive_parcel',
        'total_amount',
        'receive_amount',
        'payment_status',
        'payment_note',
        'created_branch_user_id',
        'updated_branch_user_id',
        'updated_admin_user_id',
        'branch_id',
        'payment_date'
    ];

    public $timestamps = true;

    public function booking_parcel_payment_logs()
    {
        return $this->hasMany(BookingParcelPaymentLog::class, 'payment_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function branch_user()
    {
        return $this->belongsTo(BranchUser::class, 'created_branch_user_id', 'id');
    }
}
