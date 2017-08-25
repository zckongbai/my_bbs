<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public $user;

    public function __construct()
    {
        $userId = session('userId');
        $this->user = $userId ? User::find($userId) : null;
    }

    /**
     * @param $name
     * @param $data array
     * @return \Illuminate\View\View
     */
    protected function view($name, $data = [])
    {
        $common = $this->getCommonViewData();
        return view($name, array_merge($common, $data));
    }

    /**
     * @return array
     */
    protected function getCommonViewData()
    {
        $data = [];
        if (is_object($this->user)) {
            $data['user'] = $this->user;
        }
        return $data;
    }

    /**
     * 返回json格式数据
     * @param $code  string
     * @param $data  array
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function json($code, $data)
    {
        $json['code'] = $code;
        $json['data'] = $data;
        return response()->json($json);
    }

    /**
     * 返回前一个url
     * @return string
     */
    public function back()
    {
        $backUrl = session()->pull('backUrl') ? :
            (app('request')->get('backUrl') ? :
                app('request')->header('referer'));
        // 排除登录
        if (false !== stripos($backUrl, 'login')) {
            $backUrl = url('user');
        }

        return redirect($backUrl);
    }

}
