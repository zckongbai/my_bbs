<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 权限表

        Schema::create('permission', function (Blueprint $table){
            $table->smallIncrements('id');
            $table->string('name', 64)->comment('权限名称');
            $table->string('controller', 64)->comment('请求控制器');
            $table->string('action', 64)->comment('请求方法');
            $table->string('uses', 128)->comment('uses');
            $table->timestamps();

            $table->index('uses', 'index_uses');
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
