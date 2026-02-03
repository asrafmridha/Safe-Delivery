<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->string('email', 200);
            $table->text('subject');
            $table->text('message');
            $table->tinyInteger('status')->default(1)->comment('1_unread, 2_read');
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
        Schema::dropIfExists('visitor_messages');
    }
}
