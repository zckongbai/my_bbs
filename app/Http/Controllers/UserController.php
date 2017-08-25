<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Cache;
use Log;


class UserController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 注册页面
     */
    public function register()
    {
        return $this->view('user.register');
    }

    /**
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Lumen\Http\Redirector|string
     */
    public function doRegister(Request $request)
    {
        $this->validate($request, [
            'name'  =>  'required',
            'email' => 'required|email|unique:user',
            'password' => 'required',
            'surePassword' => 'same:password',
        ]);

        $password = password_hash($request->input('password'), PASSWORD_DEFAULT);
        $roleId = DB::table('role')->where('name', 'user')->value('id');
        $data = [
            'name'  =>  $request->input('name'),
            'email' =>  $request->input('email'),
            'password' => $password,
            'role_id' => $roleId,
        ];

        $user = User::create($data);
        if ($user) {
            // 缓存用户信息
            $this->cacheUserInfo($user);

            return $this->back();
        }
        return redirect('user/login')->withErrors(['error' => '服务器忙,请重试']);
    }

    public function update($id)
    {

    }

    public function index(Request $request)
    {
        return $this->view('user.index');
    }

    /**
     *  5分支内登陆3次
     * @param Request $request
     * @return bool
     */
    protected function limitLoginByIp($ip)
    {
        $key = 'limitLoginByIp:'.$ip;
        $time = Cache::remember($key, 5, function () {
            return 1;
        });
        Log::info('login limit by ip', ['ip' => $ip, 'time' => $time]);

        if ($time < 3) {
            Cache::increment($key, 1);
            return true;
        }
        return false;
    }

    /**
     * 取消登录限制
     * @param $ip
     * @return bool
     */
    protected function delLimitLoginByIp($ip)
    {
        $key = 'limitLoginByIp:'.$ip;
        Cache::forget($key);
        Log::info(__METHOD__, ['ip' => $ip]);
        return true;
    }


    /**
     * 登录页面
     * @param Request $request
     * @return
     */
    public function login(Request $request)
    {
        if (self::checkUserIsLogin()) {
            return redirect('user');
        }
        return $this->view('user.login');
    }

    /**
     * 处理登录
     * @param Request $request
     * @return
     */
    public function doLogin(Request $request)
    {
        // 次数限制
        if (!$this->limitLoginByIp($request->getClientIp())) {
            return redirect('user/login')->withErrors(['errors'=>'ip 登录超过限制,5分钟后重试']);
        }

        $this->validate($request, [
            'email' => 'required|email|exists:user,email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!password_verify($request->input('password'), $user->password)) {
            return redirect('user/login')->withErrors(['errors'=>'password error!']);
        }

        // cache user id into session
        $this->cacheUserInfo($user);

        // 清空登录限制
        $this->delLimitLoginByIp($request->getClientIp());

        // write log
        Log::info('user login success', ['id' => $user->id, 'ip' => $request->getClientIp()]);

        // back url
        return $this->back();

    }


    /**
     * 缓存用户信息
     * @param Request $request
     * @param string $id
     * @param string $roleName
     * @return bool
     */
    protected function cacheUserInfo(User $user)
    {
        if ($user) {
            setcookie('session', session_id(), 3600);
            session()->put('userId', $user->id);
            return true;
        }
    }

    /**
     * 退出
     */
    public function logout(Request $request)
    {
        setcookie('session', session_id(), time()-1);
        $request->session()->pull('userId');

        return $this->view('user.logout');
    }

    /**
     * 我的发帖
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function topics(Request $request)
    {
        $topics = \App\Models\Topic::where('user_id', $this->user->id)->paginate(10);

        return $this->view('user.topics', ['topics'=>$topics]);
    }

    /**
     * 发出的回复
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function sendReplies(Request $request)
    {
        $replies = $this->user->replies()->paginate(10);

        return $this->view('user.sendReplies', ['replies'=>$replies]);
    }

    /**
     * 收到的回复
     * @param Request $request
     */
    public function getReplies(Request $request)
    {
        $replies = $this->user->getReplies()->paginate(10);
        return $this->view('user.getReplies', ['replies'=>$replies]);
    }

    /**
     * 检查用户是否登录
     * @return mixed
     */
    public static function checkUserIsLogin()
    {
        return session()->has('userId');
    }

}
