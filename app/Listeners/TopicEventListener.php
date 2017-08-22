<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/22
 * Time: 下午3:08
 */

namespace App\Listeners;

use App\Models\Section;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;


class TopicEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\TopicEvent',
            'App\Listeners\TopicEventListener@handle'
        );
    }

    public function handle($event)
    {
        switch ($event->type){
            // 更新浏览数
            case 'get':
                $event->topic->click_number++;
                $event->topic->save();
                Log::info(
                    'topic click_number increment by TopicEventListener',
                    ['id' => $event->topic->id, 'click_number' => $event->topic->click_number]
                );
                break;
            // 更新板块发帖数
            case 'add':
                $section = $event->topic->section;
                $section->topic_number++;
                $section->save();
                Log::info(
                    'section click_number increment by TopicEventListener',
                    ['id' => $event->topic->id, 'click_number' => $section->topic_number]
                );
                break;
            default:
                break;
        }

    }

}