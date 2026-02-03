<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingParcel extends Model
{
    use HasFactory;

    protected $fillable = [
        'parcel_code',
        'booking_parcel_type',
        'merchant_id',
        'rider_id',
        'sender_name',
        'sender_phone',
        'sender_nid',
        'sender_address',
        'sender_division_id',
        'sender_district_id',
        'sender_thana_id',
        'sender_area_id',
        'sender_branch_id',
        'receiver_name',
        'receiver_phone',
        'receiver_address',
        'receiver_division_id',
        'receiver_district_id',
        'receiver_thana_id',
        'receiver_area_id',
        'receiver_branch_id',
        'delivery_type',
        'note',
        'total_amount',
        'pickup_charge',
        'discount_percent',
        'discount_amount',
        'vat_percent',
        'vat_amount',
        'grand_amount',
        'net_amount',
        'paid_amount',
        'due_amount',
        'collection_amount',
        'cod_percent',
        'cod_amount',
        'customer_due_amount',
        'customer_collected_amount',
        'status',
        'vehicle_id',
        'sender_warehouse_id',
        'sender_warehouse_type',
        'receiver_warehouse_id',
        'receiver_warehouse_type',
        'vehicle_warehouse_status',
        'created_branch_user_id',
        'updated_branch_user_id',
        'updated_admin_user_id',
        'booking_date',
        'created_at',
        'updated_at'
    ];

    public $timestamps  = true;


    public function booking_parcel_logs(){
        return $this->hasMany(BookingParcelLog::class, 'booking_id', 'id');
    }

    public function booking_items(){
        return $this->hasMany(BookingItem::class, 'booking_id', 'id')->with('item');
    }

    public function sender_branch() {
        return $this->belongsTo(Branch::class, 'sender_branch_id');
    }

    public function receiver_branch() {
        return $this->belongsTo(Branch::class, 'receiver_branch_id');
    }

    public function sender_division() {
        return $this->belongsTo(Division::class, 'sender_division_id');
    }

    public function sender_district() {
        return $this->belongsTo(District::class, 'sender_district_id');
    }

    public function sender_upazila() {
        return $this->belongsTo(Upazila::class, 'sender_thana_id');
    }

    public function sender_area() {
        return $this->belongsTo(Area::class, 'sender_area_id');
    }

    public function receiver_division() {
        return $this->belongsTo(Division::class, 'receiver_division_id');
    }

    public function receiver_district() {
        return $this->belongsTo(District::class, 'receiver_district_id');
    }

    public function receiver_upazila() {
        return $this->belongsTo(Upazila::class, 'receiver_thana_id');
    }

    public function receiver_area() {
        return $this->belongsTo(Area::class, 'receiver_area_id');
    }

    public function booking_vehicle_plists()
    {
        return $this->hasMany(BookingVehiclePlist::class, 'booking_id', 'id');
    }

    public function sender_warehouses()
    {
        return $this->belongsTo(Warehouse::class, 'sender_warehouse_id', 'id');
    }

    public function receiver_warehouses()
    {
        return $this->belongsTo(Warehouse::class, 'receiver_warehouse_id', 'id');
    }

    public function booking_parcel_payment_details()
    {
        return $this->hasOne(BookingParcelPaymentDetails::class, 'booking_id', 'id');
    }

//    public function created_admin() {
//        return $this->belongsTo(Admin::class, 'created_admin_id')->withDefault(['name' => 'Admin User']);
//    }
//
//    public function updated_admin() {
//        return $this->belongsTo(Admin::class, 'updated_admin_id')->withDefault(['name' => 'Admin User']);
//    }
}
