<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingParcelPaymentDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'payment_receive_type',
        'collection_amount',
        'cod_charge',
        'delivery_charge',
        'return_charge',
        'total_amount',
        'branch_id',
        'created_branch_user_id',
        'updated_branch_user_id',
        'updated_admin_user_id',
        'payment_date',
        'forward_date',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    public function booking_parcels()
    {
        return $this->belongsTo(BookingParcel::class, 'booking_id', 'id');
    }
}
