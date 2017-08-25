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
     * 注册
     */
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {

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

                $redirectUrl = session()->pull('backUrl', url('user'));
                return redirect($redirectUrl);
            }
        }
        return $this->view('user.register');
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
     * @param Request $request
     * @return
     */
    public function login(Request $request)
    {
        if (self::checkUserIsLogin()) {
            return redirect('user/index');
        }

        if ($request->isMethod('post')) {
            // 次数限制
            if (!$this->limitLoginByIp($request->getClientIp())) {
                return $this->view('user.login')->withErrors(['errors'=>'ip 登录超过限制,5分钟后重试']);
            }

            $this->validate($request, [
                'email' => 'required|email|exists:user,email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->input('email'))->first();

            if (!password_verify($request->input('password'), $user->password)) {
                return $this->view('user.login')->withErrors(['message'=>'password error!']);
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
        return $this->view('user.login');
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
            session()->put('user_id', $user->id);
            return true;
        }
    }

    /**
     * 退出
     */
    public function logout(Request $request)
    {
        setcookie('session', session_id(), time()-1);
        $request->session()->pull('user_id');

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
       // var_dump(DB::getQueryLog());
        return $this->view('user.getReplies', ['replies'=>$replies]);
    }

    /**
     * 检查用户是否登录
     * @return mixed
     */
    public static function checkUserIsLogin()
    {
        return session()->has('user_id');
    }

}
