<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'branch_id',
        'vehicle_status',
        'assign_date',
        'created_at',
        'updated_at'
    ];

    public $timestamps  = true;

    public function booking_vehicle_plists()
    {
        return $this->hasMany(BookingVehiclePlist::class, 'master_id', 'id');
    }

    public function vehicles(){
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }

    public function branches(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}
