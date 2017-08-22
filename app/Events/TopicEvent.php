<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/22
 * Time: 下午3:04
 * 帖子相关事件
 */

namespace App\Events;

use App\Models\Topic;

class TopicEvent
{

    public $topic;

    public function __construct(Topic $topic, $type)
    {
        $this->topic = $topic;
        $this->type = $type;
    }


}