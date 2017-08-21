<?php
/**
 * Created by PhpStorm.
 * User: zhangchao
 * Date: 17/8/17
 * Time: 下午1:58
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Log;

class RoleController extends Controller
{
    protected static $cacheAllRoleKey = 'allRoleKey';
    /**
     * @param Request $request
     *
    `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(32) NOT NULL,
    `status` enum('0','1') NOT NULL DEFAULT '1',
    `remark` varchar(64) DEFAULT NULL,
     */
    public function add(Request $request)
    {
        $input = $request->only('name','status','remark');
        $validator = Validator::make($input, [
            'name' => 'required|max:32',
            'status' => 'required|in:0,1',
            'remark' => 'max:64'
        ]);

        if ($validator->fails()){
            $error = $validator->errors();
            return json_encode(['code'=>1001, 'message'=>'表单验证失败', 'error'=>$error]);
        }

        try{
            $res = DB::table('role')->insert($input);
        } catch (\Exception $e){
            $errorMsg = DB::getQueryLog();
            $errorMsg = $e->getMessage();
            Log::info('role add failed', ['errorMsg'=>$errorMsg, 'input'=>$input]);
            return json_encode(['code'=>1002, 'message'=>'失败', 'error'=>$errorMsg]);
        }
        $this->setCacheAllRole();
        return json_encode(['code'=>0, 'message'=>'success','redirectUrl'=>url('role/index')]);
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id'    =>  'required|numeric',
            'name' => 'required|max:32',
            'status' => 'required|in:0,1',
            'remark' => 'max:64'
        ]);
        if ($validator->fails()){
            $error = $validator->errors();
            return json_encode(['code'=>1001, 'message'=>'表单验证失败', 'error'=>$error]);
        }

        try {
            $res = DB::table('role')
                ->where('id', $input['id'])
                ->update($input);
        }catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            Log::info('permission add failed', ['errorMsg'=>$errorMsg, 'input'=>$input]);
            return json_encode(['code'=>1002, 'message'=>'失败', 'error'=>$errorMsg]);
        }
        $this->setCacheAllRole();
        return json_encode(['code'=>0, 'message'=>'success','redirectUrl'=>url('role/index')]);
    }

    public function delete(Request $request, $id)
    {
        if (empty($id) || ! is_numeric($id)){
            return json_encode(['code'=>1001, 'message'=>'id不正确']);
        }

        $res = DB::table('role')->where('id', $id)->delete();

        if (false === $res) {
            return json_encode(['code'=>1003, 'message'=>'failed']);
        }
        $this->setCacheAllRole();
        return json_encode(['code'=>0, 'message'=>'success','redirectUrl'=>url('role/index')]);
    }

    public function index(Request $request)
    {
        $pIndex = $request->input('p', 1);
        $pSize = 10;
        $data = DB::table('role')
            ->offset($pSize * ($pIndex -1))
            ->limit($pSize)
            ->get();
        return json_encode(['code'=>0, 'message'=>'success', 'data'=>$data]);
    }

    /**
     * 设置缓存所有的role记录
     */
    protected function setCacheAllRole()
    {
        $role = DB::table('role')->get();
        Cache::store('redis')->forever(self::$cacheAllRoleKey, $role);
        return true;
    }

    /**
     * 获取所有的role
     * @return mixed arr
     */
    public function getAllRole()
    {
        $role = Cache::store('redis')->get(self::$cacheAllRoleKey);
        if (empty($role)){
            $role = DB::table('role')->get();
            Cache::store('redis')->forever(self::$cacheAllRoleKey, $role);
        }
        return $role;
    }

    /**
     * @param $request
     * @return bool
     */
    public function getVisitorId(Request $request)
    {
        $id = DB::table('role')
            ->where('name', 'role')
            ->get();
    }

}