<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/17
 * Time: 下午7:49
 */

namespace App\Http\Controllers;

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
        if ('POST' == $request->getMethod()) {

//            $this->validate($request, [
//                'name' => 'required|max:64',
//            ]);

            $input = $request->all();
            if (empty($input)){
                return view('section.add', ['errorMsg' => '表单不能为空']);
            }
            $validator = Validator::make($input, [
                'name' => 'required|max:64',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors();
                return view('section.add', ['error' => $error]);
            }

            try {
                DB::table('section')->insert(['name'=>$input['name']]);
            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                return view('section.add', ['errorMsg'=>$errorMsg]);
            }

            Log::info(__CLASS__ .':success', ['time'=>date('Y-m-d H:i:s'),'input'=>$input]);
            return redirect('section/index');
        }
        return view('section.add');
    }
    public function update(Request $request)
    {

    }
    public function delete(Request $request)
    {

    }
    public function index(Request $request)
    {

    }

    public static function getAll()
    {
        return DB::table('section')->get();
    }

}