<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 用户-角色
        Schema::create('user_role', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('user id');
            $table->unsignedSmallInteger('role_id')->comment('role id');
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
    }
}
