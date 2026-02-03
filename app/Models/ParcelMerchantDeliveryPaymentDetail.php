<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelMerchantDeliveryPaymentDetail extends Model {
    use HasFactory;

    protected $guarded = [];

    public function parcel() {
        return $this->belongsTo(Parcel::class, 'parcel_id')->with('merchant', 'weight_package');
    }

    public function parcel_merchant_delivery_payment() {
        return $this->belongsTo(ParcelMerchantDeliveryPayment::class, 'parcel_merchant_delivery_payment_id', 'id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
