<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->string('password', 100);
            $table->string('store_password', 100);
            $table->string('image', 100)->nullable();

            $table->string('address', 255)->nullable();
            $table->string('contact_number', 150);
            $table->integer('district_id')->nullable();
            $table->integer('upazila_id')->nullable();
            $table->integer('area_id')->nullable();

            $table->date('date');


            $table->string('otp_token',6);
            $table->dateTime('otp_token_created');
            $table->dateTime('otp_token_status');
            $table->dateTime('otp_token_saved_browser');

            $table->tinyInteger('status')->default(1)->comment('1_for_active, 0_for_inactive');
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
        Schema::dropIfExists('branches');
    }
}
