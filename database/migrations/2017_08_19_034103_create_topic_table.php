<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 帖子
        Schema::create('topic', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedMediumInteger('section_id')->comment('板块id');
            $table->unsignedInteger('user_id')->comment('user id');
            $table->string('title', 64)->comment('标题');
            $table->text('content')->comment('内容');
            $table->unsignedMediumInteger('click_number')->default(0)->comment('点击数');
            $table->unsignedMediumInteger('reply_number')->default(0)->comment('回复数');
            $table->unsignedTinyInteger('status')->default(1)->comment('帖子的评论状态,1是开放,2是关闭');
            $table->timestamp('last_reply_at')->nullAllow()->comment('最后回帖时间');
            $table->softDeletes();
            $table->timestamps();
            $table->index('user_id', 'index_user_id');

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
        Schema::drop('topic');
    }
}
