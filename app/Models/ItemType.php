<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function service_area()
    {
        return $this->belongsTo(ServiceArea::class,'service_area_id','id');
    }
}
