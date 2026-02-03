<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelDeliveryPaymentDetail extends Model{
    use HasFactory;

    protected $guarded  = [];

    public function parcel() {
        return $this->belongsTo(Parcel::class, 'parcel_id')->with('merchant');
    }

    public function parcel_delivery_payment() {
        return $this->belongsTo(ParcelDeliveryPayment::class, 'parcel_delivery_payment_id', 'id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
