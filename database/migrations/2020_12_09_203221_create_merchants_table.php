<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->string('password', 100);
            $table->string('store_password', 100);
            $table->string('image', 100)->nullable();

            $table->string('company_name', 150);
            $table->string('address', 150)->nullable();
            $table->string('contact_number', 150);
            $table->integer('district_id')->nullable();
            $table->integer('upazila_id')->nullable();
            $table->integer('area_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->float('cod', 6,2)->default(0);
            $table->date('date');

            $table->string('otp_token',6)->nullable();
            $table->dateTime('otp_token_created')->nullable();
            $table->tinyInteger('otp_token_status')->nullable()->comment('0_for_otp_send, 1_for_otp_confirmed	');


            $table->integer('created_admin_id')->nullable();
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
        Schema::dropIfExists('merchants');
    }
}
