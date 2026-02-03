<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnBranchTransferDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_branch_transfer_id',
        'parcel_id',
        'note',
        'status',
    ];


    public function return_branch_transfer() {
        return $this->belongsTo(ReturnBranchTransfer::class, 'return_branch_transfer_id')->withDefault(['name' => 'Return Transfer']);
    }

    public function parcel() {
        return $this->belongsTo(Parcel::class, 'parcel_id')->withDefault(['name' => 'Parcel'])->with('merchant');
    }
}
