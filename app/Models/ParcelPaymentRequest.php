<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelPaymentRequest extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function merchant() {
        return $this->belongsTo(Merchant::class, 'merchant_id')->withDefault(['name' => 'Merchant ']);
    }

    public function parcel_merchant_delivery_payment() {
        return $this->belongsTo(ParcelMerchantDeliveryPayment::class, 'parcel_merchant_delivery_payment_id', 'id');
    }
}
