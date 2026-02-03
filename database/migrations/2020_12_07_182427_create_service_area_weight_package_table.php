<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceAreaWeightPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_area_weight_package', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_area_id');
            $table->integer('weight_package_id');
            $table->integer('service_area_setting_id');
            $table->float('rate',8,2);
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
        Schema::dropIfExists('service_area_weight_package');
    }
}
