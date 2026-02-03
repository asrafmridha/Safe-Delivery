<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
