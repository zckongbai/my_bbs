<html>
<head>
    <title>应用程序名称 - @yield('title')</title>
    <script
            src="http://code.jquery.com/jquery-3.2.1.js"
            integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
            crossorigin="anonymous"></script>
</head>
<body>

@section('header')

    <a href="{{ url('home/index') }}">首页</a>

    @if(\App\Http\Controllers\UserController::checkUserIsLogin())
    {{--@if(session('user_id'))--}}
        <ul>
            <li><a href="{{ url('user/index') }}">{{ $user->name }}</a></li>
            <li><a href="{{ url('user/logout') }}">退出</a></li>
        </ul>
    @else
        <ul>
            <li><a href="{{ url('user/login') }}" >登录</a></li>
            <li><a href="{{ url('user/register') }}">注册</a></li>
        </ul>
    @endif
@show

@section('sidebar')
    侧边栏

@show

<div class="container">
    @yield('content')
</div>


</body>
@yield('script')

</html>