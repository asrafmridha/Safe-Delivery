<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Merchant extends Authenticatable implements JWTSubject {

    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'm_id',
        'name',
        'email',
        'password',
        'store_password',
        'image',
        'company_name',
        'address',
        'business_address',
        'fb_url',
        'web_url',
        'bank_name',
        'bank_account_no',
        'bank_account_name',
        'bkash_number',
        'nagad_number',
        'rocket_name',
        'nid_no',
        'nid_card',
        'trade_license',
        'tin_certificate',
        'contact_number',
        'district_id',
        'upazila_id',
        'branch_id',
        'on_board_branch_id',
        'area_id',
        'date',
        'cod_charge',
        'otp_token',
        'otp_token_created',
        'otp_token_status',
        'status',
        'created_admin_id',
        'updated_admin_id',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
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

    public function service_area_charges() {
        return $this->belongsToMany(ServiceArea::class, 'merchant_service_area_charges', 'merchant_id', 'service_area_id')
                    ->withPivot('charge')
                    ->withTimestamps();
    }

    public function service_area_return_charges() {
        return $this->belongsToMany(ServiceArea::class, 'merchant_service_area_return_charges', 'merchant_id', 'service_area_id')
                    ->withPivot('return_charge')
                    ->withTimestamps();
    }

    public function service_area_cod_charges() {
        return $this->belongsToMany(ServiceArea::class, 'merchant_service_area_cod_charges', 'merchant_id', 'service_area_id')
                    ->withPivot('cod_charge')
                    ->withTimestamps();
    }

    public function merchant_service_area_cod_charges() {
        return $this->hasMany(MerchantServiceAreaCodCharge::class, 'merchant_id', 'id');
    }

    public function district() {
        return $this->belongsTo(District::class, 'district_id')->withDefault(['name' => 'District Name'])->with('service_area');
    }

    public function upazila() {
        return $this->belongsTo(Upazila::class, 'upazila_id')->withDefault(['name' => 'Upazial Name']);
    }

    public function area() {
        return $this->belongsTo(Area::class, 'area_id')->withDefault(['name' => 'Area Name']);
    }

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id')->withDefault(['name' => 'Branch Not Assing']);
    }

    public function parcel() {
        return $this->hasMany(Parcel::class, 'merchant_id', 'id');
    }

    public function created_admin() {
        return $this->belongsTo(Admin::class, 'created_admin_id')->withDefault(['name' => 'Admin User']);
    }

    public function updated_admin() {
        return $this->belongsTo(Admin::class, 'updated_admin_id')->withDefault(['name' => 'Admin User']);
    }

    public function email_verifications()
    {
        return $this->hasOne(EmailVerification::class, 'user_id', 'id');
    }

    public function merchant_shops()
    {
        return $this->hasMany(\App\Models\MerchantShop::class, 'merchant_id', 'id');
    }


}
