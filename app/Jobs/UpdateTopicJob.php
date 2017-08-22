<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/22
 * Time: 下午4:06
 */

namespace App\Jobs;

use App\Models\Topic;
use Illuminate\Queue\SerializesModels;
//use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

/**
 * 更新 topic 的点击数
 * Class UpdateTopic
 * @package App\Jobs
 */
class UpdateTopicJob extends Job implements SelfHandling, ShouldQueue
{

//    use InteractsWithQueue, SerializesModels;

    public $topic;

    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    public function handle()
    {
        echo "this is UpdateTopic's handle";
        $this->topic->click_number++;
        $this->topic->save();

        Log::info('topic click_number increment', ['id' => $this->topic->id, 'click_number' => $this->topic->click_number]);
    }


}