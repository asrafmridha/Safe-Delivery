<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->integer('service_area_id');
            $table->tinyInteger('home_delivery')->default(0)->comment('0_for_no, 1_for_yes');
            $table->tinyInteger('lock_down_service')->default(0)->comment('0_for_no, 1_for_yes');
            $table->tinyInteger('status')->default(1)->comment('0_for_inactive, 1_for_active');
            $table->integer('created_admin_id');
            $table->integer('updated_admin_id')->nullable();
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
        Schema::dropIfExists('districts');
    }
}
