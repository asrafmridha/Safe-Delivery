<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelMerchantDeliveryPayment extends Model {
    use HasFactory;

    protected $guarded = [];




    public function parcel_merchant_delivery_payment_details() {
        return $this->hasMany(ParcelMerchantDeliveryPaymentDetail::class, 'parcel_merchant_delivery_payment_id')->with('parcel');
    }


    public function merchant() {
        return $this->belongsTo(Merchant::class, 'merchant_id')->withDefault(['name' => 'Merchant  Name']);
    }


    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id')->withDefault(['name' => ' ']);
    }
}
