<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'item_id',
        'item_name',
        'unit_name',
        'unit_price',
        'quantity',
        'total_item_price',
        'delivery_quantity',
        'return_quantity'
    ];

    public $timestamps  = true;

    public function booking_parcels(){
        return $this->belongsTo(BookingParcel::class, 'booking_id', 'id');
    }

    public function item(){
        return $this->belongsTo(Item::class, 'item_id')->with('item_categories', 'units');
    }


}
