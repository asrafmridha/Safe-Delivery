<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderRunDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'rider_run_id',
        'parcel_id',
        'complete_date_time',
        'note',
        'run_type',
        'status',
    ];


    public function rider_run() {
        return $this->belongsTo(RiderRun::class, 'rider_run_id')->withDefault(['name' => 'Rider Run Name']);
    }

    public function parcel() {
        return $this->belongsTo(Parcel::class, 'parcel_id')->withDefault(['name' => 'Parcel'])->with('merchant', 'area');
    }
    
     public function PathaoOrderDetail()
    {
        return $this->hasOne(PathaoOrderDetail::class);
    }

}
