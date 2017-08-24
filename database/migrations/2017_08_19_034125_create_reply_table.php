<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 回复

        Schema::create('reply', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('user id');
            $table->unsignedInteger('topic_id')->comment('topic id');
            $table->string('topic_title', 64)->comment('帖子的标题');
            $table->unsignedMediumInteger('floor')->comment('楼层');
            $table->string('content', 255)->comment('内容');
            $table->softDeletes();
            $table->timestamps();
            $table->index(['user_id','topic_id'], 'index_uid_and_tid')->change();
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
