<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('token',100);
            $table->tinyInteger('type')->comment('1_for_admin,  2_for_branch, 3_for_merchant, 4_for_rider');
            $table->tinyInteger('verification_type')->default(0)->comment('0_for_not_verified, 1_for_verified');
            $table->timestamp('date_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
}
