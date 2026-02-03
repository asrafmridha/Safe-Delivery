<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('staff_id');
            $table->decimal('salary_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2);
            $table->date('payment_month');
            $table->date('payment_date');
            $table->integer('created_admin_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_payments');
    }
}
