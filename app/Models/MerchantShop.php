<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantShop extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_name',
        'shop_address',
        'merchant_id',
        'status'
    ];


    public function merchants()
    {
        return $this->belongsTo(\App\Models\Merchant::class, 'merchant_id', 'id');
    }
}
