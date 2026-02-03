<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderRunDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_run_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rider_run_id');
            $table->integer('parcel_id');
            $table->date('run_complete_date')->nullable();
            $table->time('run_complete_time')->nullable();
            $table->text('run_complete_note')->nullable();
            $table->tinyInteger('status')->default(0)
                ->comment('0_for_run, 1_for_run_complete');
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
        Schema::dropIfExists('rider_run_details');
    }
}
