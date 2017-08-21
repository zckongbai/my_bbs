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
        //
        /**
         *
        `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(64) NOT NULL DEFAULT '' COMMENT '板块名称',
        `topic_number` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '发帖数',
        `moderator` int(11) unsigned DEFAULT NULL COMMENT '版主, user.id',
        `create_time` timestamp NULL DEFAULT NULL,
         */
        Schema::create('section', function (Blueprint $table){
            $table->smallIncrements('id');
            $table->string('name', 64);
            $table->unsignedMediumInteger('topic_number')->comment('发帖数量');
            $table->unsignedInteger('moderator')->comment('版主');
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
