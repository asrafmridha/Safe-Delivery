<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            $table->string('designation')->nullable();
            $table->text('address')->nullable();
            $table->integer('branch_id');
            $table->decimal('salary', 12, 2);
            $table->string('image', 191)->nullable();
            $table->integer('status')->default(1);
            $table->integer('created_admin_id')->default(0);
            $table->integer('updated_admin_id')->default(0);
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
        Schema::dropIfExists('staff');
    }
}
