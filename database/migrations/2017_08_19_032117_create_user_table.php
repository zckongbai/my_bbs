<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 用户表

        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 32);
            $table->string('email', 64);
            $table->unsignedSmallInteger('role_id');
            $table->string('password', 255);
            $table->string('salt', 32)->comment('盐值');
            $table->softDeletes();
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
        //
        Schema::drop('users');
    }
}
