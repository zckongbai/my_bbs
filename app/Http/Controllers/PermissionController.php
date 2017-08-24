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
            return $this->json(1001, ['message'=>'表单验证失败', 'error'=>$validator->errors()]);
        }

        $permission = new Permission();
        $permission->name = $request->input('name');
        $permission->controller = ucwords($request->input('controller'));
        $permission->action = $request->input('action');
        $permission->uses = $permission->controller . '@' . $permission->action;
        $permission->save();

        return $this->json(0, ['message'=>'success']);
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id'    =>  'required|numeric|exists:permission',
            'name' => 'required',
            'controller' => 'required',
            'action' => 'required',
        ]);
        if ($validator->fails()){
            return $this->json(1001, ['message'=>'表单验证失败', 'error'=>$validator->errors()]);
        }

        $permission = Permission::find($input['id']);
        $permission->name = $input['name'];
        $permission->controller = $input['controller'];
        $permission->action = $input['action'];
        $permission->save();

        return $this->json(0, ['message'=>'success']);
    }

    public function delete($id)
    {
        if (empty($id) || !is_numeric($id)){
            return $this->json(1001, ['message'=>'权限不存在']);
        }

        Permission::destroy($id);

        $res = Log::info('permission delete', ['operator'=>$this->user->id]);

        if (false == $res) {
            return $this->json(5000, ['message'=>'failed']);
        }
        return $this->json(0, ['message'=>'success']);
    }

    public function index()
    {
        $data = Permission::paginate(10)->toArray();
        return $this->json(0, ['message'=>'success', 'data'=>$data]);
    }
}