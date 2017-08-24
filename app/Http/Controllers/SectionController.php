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
    /**
     * @param Request $request
     * @return string
     */
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {

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
            return $this->view('section.add')->withErrors(['errors' => '失败']);
        }
        return $this->view('section.add');
    }

    public function update(Request $request, $id)
    {
        $section = Section::find($id);
        if ($request->isMethod('post')){
            $input = $request->all();
            $validator = Validator::make($input, [
                'name' => 'required|max:64|unique:section',
            ]);
            if ($validator->fails()){
                return $this->view('section.update')->withErrors($validator);
            }

            $section->name = $input['name'];
            $section->save();

            return redirect(url('section', ['id'=>$section->id]));

        }
        return $this->view('section.update', ['section' => $section]);
    }
    public function delete(Request $request)
    {

    }
    public function index(Request $request)
    {
        $sections = Section::paginate(10);
        return $this->view('section.index', ['sections'=>$sections]);
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