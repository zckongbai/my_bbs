<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //删除user salt字段
        Schema::table('user', function (Blueprint $table){
            $table->dropColumn('salt');
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
        Schema::table('user', function (Blueprint $table){
            $table->string('salt', 32)->comment('盐值');
        });
    }
}
