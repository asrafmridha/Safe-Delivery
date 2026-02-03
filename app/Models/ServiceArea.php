<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceArea extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'cod_charge', 'weight_type', 'default_charge', 'delivery_time', 'details', 'status', 'created_admin_id', 'updated_admin_id',
    ];

    public function weight_packages() {
        return $this->belongsToMany(WeightPackage::class, 'service_area_weight_package', 'service_area_id', 'weight_package_id')
                    ->withPivot('rate')
                    ->withTimestamps();
    }

    public function created_admin() {
        return $this->belongsTo(Admin::class, 'created_admin_id')->withDefault(['name' => 'Admin User']);
    }

    public function updated_admin() {
        return $this->belongsTo(Admin::class, 'updated_admin_id')->withDefault(['name' => 'Admin User']);
    }

    public function service_type()
    {
       return $this->hasMany(ServiceType::class,'service_area_id','id');
    }
    public function item_type()
    {
       return $this->hasMany(ItemType::class,'service_area_id','id');
    }
}
