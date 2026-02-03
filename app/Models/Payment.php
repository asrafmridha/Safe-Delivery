<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function created_user()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function supplier()
    {
        return $this->belongsTo(Client::class,'supplier_id','id');
    }
}
