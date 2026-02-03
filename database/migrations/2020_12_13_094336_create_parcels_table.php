<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcels', function (Blueprint $table) {
            $table->increments('id');

            $table->string('parcel_invoice',50);
            $table->integer('merchant_id');
            $table->string('order_id', 255)->nullable();
            $table->date('date')->comment('Parcel Generate Date');
            $table->date('delivery_date')->nullable();
            $table->string('parcel_code', 10)->nullable();

            $table->string('customer_name', 255);
            $table->text('customer_address');
            $table->string('customer_contact_number', 100);
            $table->text('product_details');
            $table->integer('district_id')->default(0);
            $table->integer('upazila_id')->default(0);
            $table->integer('area_id')->default(0);

            $table->integer('weight_package_id')->default(0);
            $table->float('delivery_charge', 8,2)->default(0);

            $table->float('total_collect_amount',  8,2)->default(0);
            $table->float('cod_percent',  8,2)->default(0);
            $table->float('cod_charge', 8,2)->default(0);

            $table->float('total_charge',  8,2)->default(0);
            $table->float('customer_collect_amount',  8,2)->default(0);

            $table->bigInteger('delivery_option_id')->default(0);


            $table->text('pickup_address')->nullable();
            $table->bigInteger('pickup_branch_id')->nullable();
            $table->date('pickup_branch_date')->nullable();

            $table->bigInteger('pickup_rider_id')->nullable();
            $table->date('pickup_rider_date')->nullable();


            $table->bigInteger('delivery_branch_id')->nullable();
            $table->date('delivery_branch_date')->nullable();

            $table->bigInteger('delivery_rider_id')->nullable();
            $table->date('delivery_rider_date')->nullable();

            $table->text('parcel_note')->nullable();
            $table->date('parcel_date')->comment('Parcel Transfer Date');
            $table->date('reschedule_parcel_date')->nullable()->comment('If Parcel has Rescheduled');


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

            $table->bigInteger('created_admin_id')->nullable();
            $table->bigInteger('updated_admin_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('parcels');
    }
}
