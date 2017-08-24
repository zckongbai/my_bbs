<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/17
 * Time: 下午7:49
 */

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Cache;
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

            $input = $request->all();
            if (empty($input)){
                return $this->view('section.add')->withErrors(['form' => '表单不能为空']);
            }
            $validator = Validator::make($input, [
                'name' => 'required|max:64|unique:section',
            ]);

            if ($validator->fails()) {
                return $this->view('section.add', ['error' => $validator->errors()]);
            }

            $section = Section::create($input);

            if ($section){
                Log::info('section add success', ['operator' => $this->user->id, 'input' => $input]);
                return $this->view('section/index');
            }
            return $this->view('section/add')->withErrors(['form' => '失败'])->withInput();
        }
        return $this->view('section.add');
    }

    public function update(Request $request, $id)
    {
        $section = Section::find($id);
        if ($request->isMethod('post')){
            $input = $request->all();
            $validator = VAlidator::make($input, [
                'name' => 'required|max:64|unique:section',
            ]);
            if ($validator->fails()){
                return $this->view('section.update')->withErrors($validator);
            }
        }
        return $this->view('section.update', ['section' => $section]);
    }
    public function delete(Request $request)
    {

    }
    public function index(Request $request)
    {

    }

    public static function getAll()
    {
    }

}