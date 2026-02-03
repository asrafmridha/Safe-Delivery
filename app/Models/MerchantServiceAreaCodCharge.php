<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantServiceAreaCodCharge extends Model {
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'service_area_id',
        'cod_charge',
    ];

    public function merchant() {
        return $this->belongsTo(Merchant::class, 'merchant_id')
            ->withDefault(['name' => 'Merchant Name']);
    }

    public function service_area()
    {
        return $this->belongsTo(ServiceArea::class, 'service_area_id')
        ->withDefault(['name' => 'Service Area Name']);
    }
}
