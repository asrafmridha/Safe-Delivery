<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rider_id');
            $table->decimal('salary_amount', 12, 2);
            $table->double('km')->nullable()->default(0);
            $table->double('km_commission')->nullable()->default(0);
            $table->double('total_km_commission')->nullable()->default(0);
            $table->double('total_parcel')->nullable()->default(0);
            $table->double('parcel_commission')->nullable()->default(0);
            $table->double('total_parcel_commission')->nullable()->default(0);
            $table->double('total_weight')->nullable()->default(0);
            $table->double('weight_commission')->nullable()->default(0);
            $table->double('total_weight_commission')->nullable()->default(0);
            $table->double('total_amount');
            $table->double('paid_amount');
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
        Schema::dropIfExists('rider_payments');
    }
}
