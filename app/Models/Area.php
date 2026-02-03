<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'post_code', 'district_id', 'upazila_id', 'created_admin_id','updated_admin_id'
    ];


    public function upazila() {
        return $this->belongsTo(Upazila::class, 'upazila_id')
        ->withDefault(['name' => 'Upazila Name'])
        ->select(['id', 'name', 'district_id', 'status'])
        ->with('district');
    }

    public function district() {
        return $this->belongsTo(District::class, 'district_id')
        ->withDefault(['name' => 'district Name']);
    }


    public function created_admin() {
        return $this->belongsTo(Admin::class, 'created_admin_id')->withDefault(['name' => 'Admin User']);
    }

    public function updated_admin() {
        return $this->belongsTo(Admin::class, 'updated_admin_id')->withDefault(['name' => 'Admin User']);
    }
}
