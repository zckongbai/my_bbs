<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;
use Cache;
use Log;
use App\Events\UserLoginEvent;


class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 注册
     */
    public function register(Request $request)
    {
        if ('POST' == $request->getMethod()){

            $this->validate($request, [
                'name'  =>  'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'surePassword' => 'same:password',
            ]);

            $salt = str_random(8);
            $password = md5($request->input('password') . $salt);
            $roleId = DB::table('role')->where('name','user')->value('id');

            $data = [
                'name'  =>  $request->input('name'),
                'email' =>  $request->input('email'),
                'password' => $password,
                'salt' => $salt,
                'role_id' => $roleId,
            ];
            $user = User::create($data);
            if ($user){
                // 更新session
                // self::cacheUserInfo($request, $user->id);

                // 改成事件
                event(new \App\Events\UserRegisterEvent($user));

                return redirect('user');
            }

        }
        return view('user.register');
    }


    public function update($id)
    {

    }

    public function index(Request $request)
    {
        return view('user.index');
    }

    /**
     *  5分支内登陆3次
     * @param Request $request
     * @return bool
     */
    public function loginLimit($ip)
    {
        $time = Cache::remember($ip, 5, function (){
            return 1;
        });
        if ($time < 3){
            Cache::increment($ip, 1);
            return true;
        }
        return false;
    }


    /**
     *  登录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|\Laravel\Lumen\Http\Redirector
     */
    public function login(Request $request)
    {
        if ($request->session()->get('is_login')){
            return redirect('user/index');
        }

        if ('POST' == $request->getMethod()) {
            $this->validate($request, [
                'email' => 'required|email|exists:users,email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->input('email'))->first();

            if (!$user){
                return redirect('user/login')->withErrors(['message'=>'nobody! check email!'])->withInput($request->except('password'));
            }

            if ($user->password !== md5($request->input('password') . $user->salt)){
                return redirect('user/login')->withErrors(['message'=>'password error!'])->withInput($request->except(['password']));
            }

            // session
            // self::cacheUserInfo($request, $user->id);
            // 改成登录事件
            event(new UserLoginEvent($user));

            // write log
            Log::info('user login:success', ['time' => date('Y-m-d H:i:s'), 'ip' => $request->getClientIp()]);

            // back url
            $redirectUrl = session()->pull('backUrl', url('user/index'));
            return redirect($redirectUrl);

        }
        return view('user.login');
    }

    /**
     * 改成事件后 废弃
     * 初始化用户信息 : 游客
     * @param Request $request
     */
    public static function initUser(Request $request)
    {
        if ($request->session()->get('is_login') || $request->session()->get('name') == User::VISITOR_NAME)
        {
            return true;
        }
        return self::cacheUserInfo($request);
    }

    /**
     * 改成事件后 废弃
     * 缓存用户信息
     * @param Request $request
     * @param string $id
     * @param string $roleName
     * @return bool
     */
    protected static function cacheUserInfo(Request $request, $id='', $roleName = User::VISITOR_NAME)
    {
        if ($id){
            $user = User::find($id);
            $data =  [
                'is_login' => true,
                'name'  =>$user->name,
                'role_id' => $user->role_id,
                'role_name' => $user->role->name,
                'uid' => $user->id,
            ];

        } else {
            $role = Role::where('name', $roleName)->first();
            $data =  [
                'is_login' => false,
                'name'  => '',
                'role_id' => $role->id,
                'role_name' => $role->name,
                'uid' => '',
            ];
        }
        setcookie('session', session_id(), 7*24*3600);
        $request->session()->put($data);
        return true;
    }

    /**
     * 退出
     */
    public function logout(Request $request)
    {
        // setcookie('session', session_id(), time()-1);
        // $request->session()->flush();

        // 改成事件
        event(new \App\Events\UserLogoutEvent());

        return view('user.logout');
    }

    /**
     * 我的发帖
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function topics(Request $request)
    {
        $topics = \App\Models\Topic::where('user_id', session('uid'))->paginate(10);

        return view('user.topics', ['topics'=>$topics]);
    }

    /**
     * 发出的回复
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function sendReplies(Request $request)
    {
        $replies = User::find(session('uid'))->replies()->paginate(10);

        return view('user.sendReplies', ['replies'=>$replies]);
    }

    /**
     * 收到的回复
     * @param Request $request
     */
    public function getReplies(Request $request)
    {
        $replies = User::find(session('uid'))->getReplies()->paginate(10);
       // var_dump(DB::getQueryLog());
        return view('user.getReplies', ['replies'=>$replies]);
    }

}
