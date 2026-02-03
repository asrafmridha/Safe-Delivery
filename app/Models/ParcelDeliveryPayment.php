<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelDeliveryPayment extends Model{
    use HasFactory;

    protected $guarded  = [];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id')->withDefault(['name' => 'Branch  Name']);
    }

    public function branch_user() {
        return $this->belongsTo(BranchUser::class, 'branch_user_id')->withDefault(['name' => 'Branch User Name']);
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id')->withDefault(['name' => ' ']);
    }

    public function parcel_delivery_payment_details() {
        return $this->hasMany(ParcelDeliveryPaymentDetail::class, 'parcel_delivery_payment_id')
        ->with('parcel');
    }

}
