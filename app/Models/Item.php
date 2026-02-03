<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    use HasFactory;

    protected $fillable = [
        'item_name',
        'item_cat_id',
        'unit_id',
        'od_rate',
        'hd_rate',
        'transit_od',
        'transit_hd',
        'status',
        'created_admin_id',
        'updated_admin_id',
    ];

    public function item_categories() {
        return $this->belongsTo(ItemCategory::class, 'item_cat_id')->withDefault(['name' => 'Category Name']);
    }

    public function units() {
        return $this->belongsTo(Unit::class, 'unit_id')->withDefault(['name' => 'Unit Name']);
    }

    public function created_admin() {
        return $this->belongsTo(Admin::class, 'created_admin_id')->withDefault(['name' => 'Admin User']);
    }

    public function updated_admin() {
        return $this->belongsTo(Admin::class, 'updated_admin_id')->withDefault(['name' => 'Admin User']);
    }
}
