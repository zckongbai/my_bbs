<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/17
 * Time: 下午1:58
 */

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Log;

class RoleController extends Controller
{
    protected static $cacheAllRoleKey = 'allRoleKey';

    public function add(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|max:32|unique:role',
        ]);

        if ($validator->fails()){
            return $this->json(1001, ['message'=>'表单验证失败', 'error'=>$validator->errors()]);
        }

        $role = Role::create($input);

        if ($role){
            Log::info(__CLASS__, ['operator'=>$this->user->id, 'input'=>$input]);
            return $this->json(0, ['message' => 'success']);
        }

        return $this->json(5000, ['message' => 'failed']);
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id'    =>  'required|numeric',
            'name' => 'required|max:32',
        ]);
        if ($validator->fails()){
            return $this->json(1001, ['message'=>'表单验证失败', 'error'=>$validator->errors()]);
        }

        $result = Role::where('id', $input['id'])->update($input);

        if ($result){
            Log::info('role update success', ['operator'=>$this->user->id, 'input'=>$input]);
            return $this->json(0, ['message'=>'success']);
        }
        return $this->json(5000, ['message'=>'失败', 'error'=>$validator->errors]);
    }

    public function delete($id)
    {
        if (empty($id) || ! is_numeric($id)){
            return json_encode(['code'=>1003, 'message'=>'角色不存在']);
        }

        $result = Role::destroy($id);

        if (false === $result) {
            Log::info('role delete success', ['message'=>'failed']);
            return $this->json(5000, ['message'=>'failed']);
        }
    }

    public function index()
    {
        $data = Role::paginate(10)->toArray();
        return $this->json(0, ['message'=>'success', 'data'=>$data]);
    }

}