<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/17
 * Time: 下午7:41
 */

namespace App\Http\Controllers;

use App\Events\TopicEvent;
use App\Models\Topic;
use App\Models\Section;
use App\Models\Reply;
use Illuminate\Http\Request;
use App\Jobs\UpdateTopicJob;
use Validator;
use Cache;
use Log;

class TopicController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 查看
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|\Laravel\Lumen\Http\Redirector
     */
    public function get(Request $request, $id)
    {
        if (!$id){
            return redirect('404');
        }

        $topic = Topic::find($id);
        // 更新浏览数
        $topic->click_number++;
        $topic->save();

        // 事件方式 更新
        //  event(new TopicEvent($topic, 'get'));

        // job方式 更新
        // $this->dispatch(new UpdateTopicJob($topic));

        return $this->view('topic.get', ['topic'=>$topic]);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function add(Request $request)
    {
        $sections = Section::all();
        if ("POST" == $request->getMethod()) {
            if (!$this->addLimit($request)){
                return redirect('topic/add')->withErrors(['errors'=>'重复提交'])->withInput();
            }

            $this->validate($request, [
                'section_id' => 'required|numeric|exists:section,id',
                'title' => 'required|max:128',
                'content' => 'required',
            ]);

            $topic = Topic::create([
                'section_id' => $request->input('section_id'),
                'status' => 1,
                'user_id' => $this->user->id,
                'title' => $request->input('title'),
                'content' => htmlspecialchars($request->input('content')),
            ]);

            if ($topic) {
               // 更新板块发帖数
               $section = Section::find($topic->section_id);
               $section->topic_number++;
               $section->save();

               Log::info(
                   'user add topic',
                   ['topic_id' => $topic->id, 'section_id' => $topic->section_id, 'topic_number' => $section->topic_number]
               );

                // 事件方式 更新板块发帖数
                // event(new TopicEvent($topic, 'add'));

                return $this->back();
            }

        }

        return $this->view('topic.add', ['sections'=>$sections]);
    }

    /**
     * @param Request $request
     * @return bool
     * 根据内容做哈希, 防止重复提交
     */
    protected function addLimit(Request $request)
    {
        $data = $request->all();
        sort($data);
        $hash = md5(json_encode($data));
        $has = Cache::has($hash);
        if (!$has){
            Cache::put($hash, 1, 1);
            return true;
        }
        return false;
    }

    /**
     * 回帖
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector
     */
    public function reply(Request $request)
    {
        $this->validate($request, [
            'topic_id' => 'required|exists:topic,id',
            'content'   =>  'required|max:255',
        ]);

        $topic = Topic::find($request->input('topic_id'));

        // 添加回复
        $reply = new Reply;
        $reply->user_id = $this->user->id;
        $reply->topic_id = $topic->id;
        $reply->topic_title = $topic->title;
        $reply->floor = $topic->reply_number + 1;
        $reply->content = $request->input('content');
        $reply->save();

        // 更新帖子
        $topic->reply_number++;
        $topic->last_reply_at = date('Y-m-d H:i:s');
        $topic->save();

        Log::info('topic reply success', ['topic'=>$topic->toArray(), 'reply'=>$reply->toArray()]);

        return redirect()->route('topic/{id}', ['id'=>$topic->id]);
    }

    public function update(Request $request)
    {

    }

    /**
     * 删除
     * @param Request $request
     * @param $id
     * @return string
     */
    public function delete(Request $request, $id)
    {
        // Topic::destroy($id);
        Topic::find($id)->delete();
        Log::info('topic delete', ['id'=>$id, 'user_id'=>$this->user->id]);
        // 返回前一页
        return $this->back();
    }
    public function search(Request $request)
    {

    }


}