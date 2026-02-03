<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnBranchTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_transfer_invoice',
        'from_branch_id',
        'from_branch_user_id',
        'to_branch_id',
        'to_branch_user_id',
        'create_date_time',
        'reject_date_time',
        'received_date_time',
        'total_transfer_parcel',
        'total_transfer_received_parcel',
        'note',
        'status',
    ];

    public function from_branch() {
        return $this->belongsTo(Branch::class, 'from_branch_id')->withDefault(['name' => 'From Branch Name']);
    }
    public function from_branch_user() {
        return $this->belongsTo(BranchUser::class, 'from_branch_user_id')->withDefault(['name' => 'From Branch User Name']);
    }

    public function to_branch() {
        return $this->belongsTo(Branch::class, 'to_branch_id')->withDefault(['name' => 'To Branch Name']);
    }
    public function to_branch_user() {
        return $this->belongsTo(BranchUser::class, 'to_branch_user_id')->withDefault(['name' => 'To Branch User Name']);
    }

    public function return_branch_transfer_details() {
        return $this->hasMany(ReturnBranchTransferDetail::class)->with('parcel');
    }
}
