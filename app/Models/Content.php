<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_type',
        'title',
        'short_details',
        'long_details',
        'photo',
        'status',
        'created_admin_id',
        'updated_admin_id'
    ];
}
