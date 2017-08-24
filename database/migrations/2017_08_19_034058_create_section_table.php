<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 板块表
        Schema::create('section', function (Blueprint $table){
            $table->smallIncrements('id');
            $table->string('name', 64);
            $table->unsignedMediumInteger('topic_number')->comment('发帖数量');
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
        Schema::drop('section');
    }
}
