<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('contact_number', 20)->unique();
            $table->string('email', 150)->unique();
            $table->string('password', 100);
            $table->string('address', 250)->nullable();
            $table->string('photo', 100)->nullable();
            $table->tinyInteger('type')->comment('1_admin, 2_general_user');
            $table->string('otp_token', 6)->nullable();
            $table->dateTime('otp_token_created')->nullable();
            $table->tinyInteger('otp_token_status')->nullable()->comment('0_for_otp_send, 1_for_otp_confirmed');
            $table->longText('otp_token_saved_browser')->nullable();
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
        Schema::dropIfExists('admins');
    }
}
