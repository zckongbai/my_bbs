@section('user')
    @if(session('is_login'))
        用户侧边栏
        <ul>
            <li><a href="{{ url('user/index') }}">我的首页</a></li>
            <li><a href="{{ url('user/topics') }}">我的发帖</a></li>
            <li><a href="{{ url('user/getReplies') }}">收到回复</a></li>
            <li><a href="{{ url('user/sendReplies') }}">我的回复</a></li>
            <li><a href="{{ url('topic/add') }}">发帖</a></li>
        </ul>
   @endif
@show