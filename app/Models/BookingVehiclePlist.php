<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingVehiclePlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'master_id',
        'booking_id',
        'active_status',
        'created_at',
        'updated_at'
    ];

    public $timestamps  = true;

    public function booking_vehicle()
    {
        return $this->belongsTo(BookingVehicle::class, 'master_id', 'id');
    }

    public function booking_parcels()
    {
        return $this->belongsTo(BookingParcel::class, 'booking_id', 'id');
    }
}
