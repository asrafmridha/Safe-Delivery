<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'publish_for',
        'short_details',
        'date_time',
        'start_date_time',
        'end_date_time',
        'status',
        'user_id'
    ];


}
