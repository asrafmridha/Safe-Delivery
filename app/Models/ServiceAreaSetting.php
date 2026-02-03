<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAreaSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['service_area_id', 'status', 'created_admin_id', 'updated_admin_id'];

    public function service_area() {
        return $this->belongsTo(ServiceArea::class, 'service_area_id', 'id')
        ->withDefault(['name' => 'Service Area']);
    }

    public function weight_packages() {
        return $this->belongsToMany(WeightPackage::class, 'service_area_weight_package', 'service_area_setting_id', 'weight_package_id')
                    ->withPivot('rate')
                    ->withTimestamps();
    }


    public function created_admin() {
        return $this->belongsTo(Admin::class, 'created_admin_id')->withDefault(['name' => 'Admin User']);
    }

    public function updated_admin() {
        return $this->belongsTo(Admin::class, 'updated_admin_id')->withDefault(['name' => 'Admin User']);
    }
}
