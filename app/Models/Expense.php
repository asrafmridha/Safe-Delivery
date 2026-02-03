<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $fillable = [
        'expense_head_id',
        'type',
        'date',
        'amount',
        'note',
        'status',
        'created_admin_id',
        'updated_admin_id'
    ];

    public function expense_heads() {
        return $this->belongsTo(ExpenseHead::class, 'expense_head_id')->withDefault(['name' => 'Head Name']);
    }

    public function created_admin() {
        return $this->belongsTo(Admin::class, 'created_admin_id')->withDefault(['name' => 'Admin User']);
    }

    public function updated_admin() {
        return $this->belongsTo(Admin::class, 'updated_admin_id')->withDefault(['name' => 'Admin User']);
    }
}
