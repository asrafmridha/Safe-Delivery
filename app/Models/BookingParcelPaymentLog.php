<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingParcelPaymentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'payment_details_id',
        'booking_id',
        'payment_status',
        'payment_note',
        'created_branch_user_id',
        'updated_branch_user_id',
        'created_admin_user_id',
        'updated_admin_user_id',
        'payment_date'
    ];

    public $timestamps = true;

    public function booking_parcel_payments()
    {
        return $this->belongsTo(BookingParcelPayment::class, 'payment_id', 'id');
    }

    public function booking_parcel_payment_details()
    {
        return $this->belongsTo(BookingParcelPaymentDetails::class, 'payment_details_id', 'id');
    }

    public function booking_parcels()
    {
        return $this->belongsTo(BookingParcel::class, 'booking_id', 'id');
    }

}
