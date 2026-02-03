<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_shops', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name', 191);
            $table->text('shop_address');
            $table->integer('merchant_id')->unsigned();
            $table->tinyInteger('status')->default(1)->comment('0_for_inactive, 1_for_active');
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
        Schema::dropIfExists('merchant_shops');
    }
}
