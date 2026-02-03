<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderRun extends Model {
    use HasFactory;

    protected $fillable = [
        'run_invoice',
        'branch_id',
        'branch_user_id',
        'rider_id',
        'create_date_time',
        'start_date_time',
        'cancel_date_time',
        'complete_date_time',
        'total_run_parcel',
        'total_run_complete_parcel',
        'note',
        'run_type',
        'status',
    ];

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id')->withDefault(['name' => 'Branch Name']);
    }
    public function branch_user() {
        return $this->belongsTo(BranchUser::class, 'branch_user_id')->withDefault(['name' => 'Branch User Name']);
    }

    public function rider() {
        return $this->belongsTo(Rider::class, 'rider_id')->withDefault(['name' => 'Rider User']);
    }

    public function rider_run_details() {
        return $this->hasMany(RiderRunDetail::class)->with('parcel');
    }

}
