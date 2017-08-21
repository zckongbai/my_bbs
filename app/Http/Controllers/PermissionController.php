<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/17
 * Time: 下午1:57
 */

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Log;

class PermissionController extends Controller
{
    /**
     * @param Request $request
     *
     */
    public function add(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'controller' => 'required',
            'action' => 'required',
            'remark' => 'max:64'
        ]);

        if ($validator->fails()){
            return redirect('permission/add')->withErrors($validator)->withInput();
        }

        $permission = new Permission();
        $permission->name = $request->input('name');
        $permission->controller = ucwords($request->input('controller'));
        $permission->action = $request->input('action');
        $permission->uses = $permission->controller . '@' . $permission->action;
        $permission->save();

        return view('permission.add');
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id'    =>  'required|numeric',
            'name' => 'required',
            'controller' => 'required',
            'action' => 'required',
            'remark' => 'max:64'
        ]);
        if ($validator->fails()){
            $error = $validator->errors();
            return json_encode(['code'=>1001, 'message'=>'表单验证失败', 'error'=>$error]);
        }

        try {
            $res = DB::table('permission')
                ->where('id', $input['id'])
                ->update($input);
        }catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            Log::info('permission add failed', ['errorMsg'=>$errorMsg, 'input'=>$input]);
            return json_encode(['code'=>1002, 'message'=>'失败', 'error'=>$errorMsg]);
        }
        return json_encode(['code'=>0, 'message'=>'success','redirectUrl'=>url('permission/index')]);
    }

    public function delete(Request $request, $id)
    {
        if (empty($id) || ! is_numeric($id)){
            return json_encode(['code'=>1001, 'message'=>'id不正确']);
        }

        $res = DB::table('permission')->where('id', $id)->delete();

        if (false === $res) {
            return json_encode(['code'=>1003, 'message'=>'failed','redirectUrl'=>url('permission/index')]);
        }
        return json_encode(['code'=>0, 'message'=>'success','redirectUrl'=>url('permission/index')]);
    }

    public function index(Request $request)
    {
        $pIndex = $request->input('p', 1);
        $pSize = 10;
        $data = DB::table('permission')
            ->offset($pSize * ($pIndex -1))
            ->limit($pSize)
            ->get();

        return json_encode(['code'=>0, 'message'=>'success', 'data'=>$data]);
    }
}