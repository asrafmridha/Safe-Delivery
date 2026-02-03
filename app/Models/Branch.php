<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Branch extends Authenticatable implements JWTSubject{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'store_password',
        'image',
        'address',
        'contact_number',
        'type',
        'parent_id',
        'district_id',
        'upazila_id',
        'area_id',
        'date',
        'status',
        'created_admin_id',
        'updated_admin_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(){
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(){
        return [];
    }


    public function parent_branch()
    {
        return $this->belongsTo(Branch::class, 'parent_id', 'id');
    }
    public function branch_users() {
        return $this->hasMany(BranchUser::class);
    }

    public function merchants() {
        return $this->hasMany(Merchant::class);
    }

    public function riders() {
        return $this->hasMany(Rider::class);
    }

    public function district() {
        return $this->belongsTo(District::class, 'district_id')->withDefault(['name' => 'District Name']);
    }

    public function upazila() {
        return $this->belongsTo(Upazila::class, 'upazila_id')->withDefault(['name' => 'Upazial Name']);
    }

    public function area() {
        return $this->belongsTo(Area::class, 'area_id')->withDefault(['name' => 'Area Name']);
    }

    public function created_admin() {
        return $this->belongsTo(Admin::class, 'created_admin_id')->withDefault(['name' => 'Admin User']);
    }

    public function updated_admin() {
        return $this->belongsTo(Admin::class, 'updated_admin_id')->withDefault(['name' => 'Admin User']);
    }
}
