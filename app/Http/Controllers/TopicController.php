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
use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\UpdateTopicJob;
use Validator;
use Cache;
use Log;

class TopicController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
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
       // // 更新浏览数
       // $topic->click_number++;
       // $topic->save();

       // 事件方式 更新
        event(new TopicEvent($topic, 'get'));

       // // job方式 更新
       // $this->dispatch(new UpdateTopicJob($topic));

        return view('topic.get', ['topic'=>$topic]);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function add(Request $request)
    {
        $sections = \App\Models\Section::all();
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
                'user_id' => session('uid'),
                'title' => $request->input('title'),
                'content' => htmlspecialchars($request->input('content')),
            ]);

            if ($topic){
               // // 更新板块发帖数
               // $section = \App\Models\Section::find($topic->section_id);
               // $section->topic_number++;
               // $section->save();

               // Log::info(
               //     'user add topic',
               //     ['id' => $topic->id, 'section_id' => $topic->section_id, 'topic_number' => $section->topic_number]
               // );

                // 事件方式 更新板块发帖数
                event(new TopicEvent($topic, 'add'));

                return redirect(url('topic', ['id'=>$topic->id]));
            }

        }

        return view('topic.add', ['sections'=>$sections]);
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

        $reply = new Reply;
        $reply->user_id = session('uid');
        $reply->topic_id = $topic->id;
        $reply->topic_title = $topic->title;
        $reply->floor = ++$topic->reply_number;
        $reply->content = $request->input('content');
        $reply->save();

        // 更新楼层
        $topic->save();

        return redirect(url('topic', ['id'=>$topic->id]));
    }

    public function update(Request $request)
    {

    }
    public function delete(Request $request)
    {

    }
    public function search(Request $request)
    {

    }


}