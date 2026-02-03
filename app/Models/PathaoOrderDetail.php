<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PathaoOrderDetail extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }

    public function rider_run_detail()
    {
        return $this->belongsTo(RiderRunDetail::class);
    }
}
