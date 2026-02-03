<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded  = [];

    public function merchant() {
        return $this->belongsTo(Merchant::class, 'merchant_id')->withDefault(['name' => 'Merchant Name']);
    }

    public function pickup_branch() {
        return $this->belongsTo(Branch::class, 'pickup_branch_id');
    }

    public function delivery_branch() {
        return $this->belongsTo(Branch::class, 'delivery_branch_id');
    }

    public function pickup_rider() {
        return $this->belongsTo(Rider::class, 'pickup_rider_id');
    }

    public function delivery_rider() {
        return $this->belongsTo(Rider::class, 'delivery_rider_id');
    }

    public function return_branch() {
        return $this->belongsTo(Branch::class, 'return_branch_id');
    }

    public function return_rider() {
        return $this->belongsTo(Rider::class, 'return_rider_id');
    }

    public function pickup_branch_user() {
        return $this->belongsTo(BranchUser::class, 'pickup_branch_user_id');
    }

    public function delivery_branch_user() {
        return $this->belongsTo(BranchUser::class, 'delivery_branch_user_id');
    }


    public function return_branch_user() {
        return $this->belongsTo(BranchUser::class, 'return_branch_user_id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id');
    }


}
