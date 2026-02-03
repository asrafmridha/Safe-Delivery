<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class WarehouseUser extends  Authenticatable implements JWTSubject
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'warehouse_id',
        'password',
        'store_password',
        'image',
        'address',
        'contact_number',
        'date',
        'otp_token',
        'otp_token_created',
        'otp_token_status',
        'otp_token_saved_browser',
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

    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id')
        ->withDefault(['name' => 'Warehouse Name']);
    }


}
