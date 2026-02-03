<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parcel extends Model
{
    // use HasFactory, SoftDeletes;
    use HasFactory;

    protected $guarded = [];


    public function weight_package()
    {
        return $this->belongsTo(WeightPackage::class, 'weight_package_id')->withDefault(['name' => 'Weight Package Name']);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id')->withDefault(['name' => 'Merchant Name'])->with('district', 'upazila', 'area');
    }

    public function merchant_shops()
    {
        return $this->belongsTo(MerchantShop::class, 'shop_id')->withDefault(['shop_name' => 'Shop Name']);
    }

    public function pickup_branch()
    {
        return $this->belongsTo(Branch::class, 'pickup_branch_id');
    }

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id', 'id');
    }

    public function item_type()
    {
        return $this->belongsTo(ItemType::class, 'item_type_id', 'id');
    }

    public function delivery_branch()
    {
        return $this->belongsTo(Branch::class, 'delivery_branch_id');
    }

    public function return_branch()
    {
        return $this->belongsTo(Branch::class, 'return_branch_id');
    }


    public function pickup_branch_user()
    {
        return $this->belongsTo(BranchUser::class, 'pickup_branch_user_id');
    }

    public function delivery_branch_user()
    {
        return $this->belongsTo(BranchUser::class, 'delivery_branch_user_id');
    }


    public function return_branch_user()
    {
        return $this->belongsTo(BranchUser::class, 'return_branch_user_id');
    }


    public function pickup_rider()
    {
        return $this->belongsTo(Rider::class, 'pickup_rider_id');
    }

    public function delivery_rider()
    {
        return $this->belongsTo(Rider::class, 'delivery_rider_id');
    }

    public function return_rider()
    {
        return $this->belongsTo(Rider::class, 'return_rider_id');
    }


    public function parcel_logs()
    {
        return $this->hasMany(ParcelLog::class, 'parcel_id');
    }
    
    public function rider_run_detail()
    {
        return $this->hasMany(RiderRunDetail::class, 'parcel_id');
    }
      //for payment details
    
    public function parcel_merchant_delivery_payment_detail()
    {
        return $this->hasOne(ParcelMerchantDeliveryPaymentDetail::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id')->withDefault(['name' => 'District Name']);
    }

    public function upazila()
    {
        return $this->belongsTo(Upazila::class, 'upazila_id')->withDefault(['name' => 'Upazial Name']);
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id')->withDefault(['name' => '']);
    }

    public function created_admin()
    {
        return $this->belongsTo(Admin::class, 'created_admin_id')->withDefault(['name' => 'Admin User']);
    }

    public function updated_admin()
    {
        return $this->belongsTo(Admin::class, 'updated_admin_id')->withDefault(['name' => 'Admin User']);
    }
}
