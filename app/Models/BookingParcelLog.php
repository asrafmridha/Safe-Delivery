<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingParcelLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'vehicle_id',
        'sender_warehouse_id',
        'sender_warehouse_type',
        'receiver_warehouse_id',
        'receiver_warehouse_type',
        'vehicle_warehouse_status',
        'note',
        'status',
        'created_branch_user_id',
        'updated_branch_user_id',
        'created_admin_user_id',
        'updated_admin_user_id',
        'created_at',
        'updated_at'
    ];

    public $timestamps  = true;


    public function booking_parcels()
    {
        return $this->belongsTo(BookingParcel::class, 'booking_id', 'id');
    }
}
