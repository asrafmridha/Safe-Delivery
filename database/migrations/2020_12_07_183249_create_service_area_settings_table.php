<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceAreaSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_area_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_area_id')->unique();
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
        Schema::dropIfExists('service_area_settings');
    }
}
