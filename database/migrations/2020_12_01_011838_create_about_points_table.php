<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('about_points', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 200);
            $table->text('details');
            $table->string('image', 100);
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
        Schema::dropIfExists('about_points');
    }
}
