<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/17
 * Time: 下午7:49
 */

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Validator;
use Log;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $sections = Section::paginate(10);
        return $this->view('section.index', ['sections'=>$sections]);
    }

    /**
     * 添加页面
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function add(Request $request)
    {
        return $this->view('section.add');
    }

    /**
     * 添加
     * @param Request $request
     */
    public function doAdd(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:64|unique:section',
        ]);

        $section = new Section;
        $section->name = $request->input('name');
        $result = $section->save();

        if ($result){
            Log::info('section add success', ['operator' => $this->user->id, ['section'=>$section->toArray()]]);
            return redirect('section');
        }
        return redirect('section/add')->withErrors(['errors' => '失败']);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:section,id',
            'name' => 'required|max:64|unique:section',
        ]);

        $id = $request->input('id');

        $result = Section::where('id', $id)->update([
            'name' => $request->input('name'),
        ]);

        if (false == $result) {
            return $this->back();
        }
        return redirect(url('section', ['id'=>$id]));
    }

    public function delete(Request $request, $id)
    {
        Section::find($id)->delete();
        Log::info(__METHOD__, ['id'=>$id, 'operator'=>$this->user->id]);
        return $this->back();
    }
    public function get($id)
    {
        $section = Section::find($id);
        return $this->view('section.get', ['section'=>$section]);
    }

    /**
     * 板块下面的帖子
     * @param $id
     * @return \Illuminate\View\View
     */
    public function topics($id)
    {
        $topics = Section::find($id)->topics()->paginate(10);
        return $this->view('section.topics', ['topics'=>$topics]);
    }
}