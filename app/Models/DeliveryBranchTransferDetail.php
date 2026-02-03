<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryBranchTransferDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_branch_transfer_id',
        'parcel_id',
        'note',
        'status',
    ];


    public function delivery_branch_transfer() {
        return $this->belongsTo(DeliveryBranchTransfer::class, 'delivery_branch_transfer_id')->withDefault(['name' => 'Delivery Transfer']);
    }

    public function parcel() {
        return $this->belongsTo(Parcel::class, 'parcel_id')->withDefault(['name' => 'Parcel'])->with('merchant');
    }
}
