<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightPackage extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'wp_id', 
        'title',
        'weight_type',
        'details', 
        'rate', 
        'status', 
        'created_admin_id', 
        'updated_admin_id',
    ];


    public function service_areas() {
        return $this->belongsToMany(ServiceArea::class, 'service_area_weight_package', 'service_area_setting_id', 'service_area_id')
                    ->withPivot('rate')
                    ->withTimestamps();
    }
    public function service_area() {
        return $this->hasOne(ServiceAreaWeightPackage::class, 'weight_package_id');
    }

    public function created_admin() {
        return $this->belongsTo(Admin::class, 'created_admin_id')->withDefault(['name' => 'Admin User']);
    }

    public function updated_admin() {
        return $this->belongsTo(Admin::class, 'updated_admin_id')->withDefault(['name' => 'Admin User']);
    }
}
