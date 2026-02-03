<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcel_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parcel_id');
            $table->integer('merchant_id')->nullable();
            $table->integer('pickup_branch_id')->nullable();
            $table->integer('pickup_rider_id')->nullable();
            $table->integer('delivery_branch_id')->nullable();
            $table->integer('delivery_rider_id')->nullable();
            $table->integer('admin_id')->nullable();

            $table->text('note')->nullable();
            $table->date('date');
            $table->time('time');

            $table->tinyInteger('status')->default(1)
                ->comment(' 1_for_merchant_create,
                            2_for_merchant_hold,
                            3_for_pickup_branch_assign_pick_rider,
                            4_for_pickup_rider_accept,
                            5_for_pickup_rider_reject,
                            6_for_pickup_rider_pickup,
                            7_for_pickup_branch_received_parcel,
                            8_for_pickup_branch_assign_delivery_branch,
                            9_for_delivery_branch_received,
                            10_for_delivery_branch_reject,
                            11_for_delivery_branch_assign_delivery_rider,
                            12_for_delivery_rider_accept,
                            13_for_delivery_rider_reject,
                            14_for_rider_return_to_delivery_branch,
                            15_for_delivery_rider_complete,
                            16_for_delivery_rider_partial_delivery,
                            17_for_delivery_rider_reschedule,
                            18_for_delivery_rider_cancel,
                        ');

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
        Schema::dropIfExists('parcel_logs');
    }
}
