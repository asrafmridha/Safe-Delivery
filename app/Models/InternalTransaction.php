<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalTransaction extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function from()
    {
        return $this->belongsTo(User::class,'from_user_id','id');
    }
    public function to()
    {
        return $this->belongsTo(User::class,'to_user_id','id');
    }
    public function created_user()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
