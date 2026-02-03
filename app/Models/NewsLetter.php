<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'email', 'status','admin_id'];


    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id')->withDefault(['name' => 'Admin User']);
    }
}
