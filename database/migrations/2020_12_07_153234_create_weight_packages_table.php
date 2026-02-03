<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeightPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weight_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',200);
            $table->string('title',100);
            $table->tinyInteger('weight_type')->default(1)->comment('1_for_kg, 2_for_cft');
            $table->text('details')->nullable();
            $table->float('rate',8,2);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('weight_packages');
    }
}
