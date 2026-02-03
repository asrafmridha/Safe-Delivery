<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderPayment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function rider()
    {
        return $this->belongsTo(Rider::class, 'rider_id')->with(['branch'])->withDefault(['name' => 'Rider Name']);
    }
}
