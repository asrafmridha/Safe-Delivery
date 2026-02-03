<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_runs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('run_invoice');
            $table->integer('rider_id');
            $table->integer('branch_id');
            $table->date('date');
            $table->time('time');
            $table->integer('total_run_parcel');
            $table->integer('total_run_complete_parcel')->default(0);
            $table->text('note')->nullable();
            $table->tinyInteger('run_type')->comment('1_for_pickup, 2_for_delivery');
            $table->tinyInteger('status')->default(1)->comment('1_for_run, 2_for_run_complete');
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
        Schema::dropIfExists('rider_runs');
    }
}
