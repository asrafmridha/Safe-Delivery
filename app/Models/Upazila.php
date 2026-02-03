<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'district_id', 'created_admin_id','updated_admin_id'
    ];


    public function district() {
        return $this->belongsTo(District::class, 'district_id')
        ->withDefault(['name' => 'District Name'])
        ->select(['id', 'name', 'service_area_id', 'home_delivery', 'lock_down_service', 'status'])
        ->with('service_area');
    }

    public function created_admin() {
        return $this->belongsTo(Admin::class, 'created_admin_id')->withDefault(['name' => 'Admin User']);
    }

    public function updated_admin() {
        return $this->belongsTo(Admin::class, 'updated_admin_id')->withDefault(['name' => 'Admin User']);
    }

}
