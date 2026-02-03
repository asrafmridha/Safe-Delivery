<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('post_code', 20);
            $table->integer('district_id');
            $table->integer('upazila_id');
            $table->tinyInteger('status')->default(1)->comment('0_for_inactive, 1_for_active');
            $table->integer('created_admin_id');
            $table->integer('updated_admin_id')->nullable();
            $table->timestamps();

            $table->unique(['name', 'district_id', 'upazila_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas');
    }
}
