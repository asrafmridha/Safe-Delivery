<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelPickupRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'pickup_request_invoice',
        'merchant_id',
        'branch_id',
        'rider_id',
        'request_type',
        'date',
        'total_parcel',
        'total_complete_parcel',
        'note',
        'action_branch_user_id',
        'action_admin_user_id',
        'status',
    ];


    public function merchant() {
        return $this->belongsTo(Merchant::class, 'merchant_id')->withDefault(['name' => 'Merchant ']);
    }

    public function riders() {
        return $this->belongsTo(Rider::class, 'rider_id')->withDefault(['name' => 'Rider']);
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function branch_user(){
        return $this->belongsTo(BranchUser::class, 'branch_user_id');
    }


}
