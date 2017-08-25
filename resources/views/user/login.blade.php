@extends('layouts.main')

@section('title', '登录')

@section('sidebar')
    @parent

    @include('layouts.user')

@endsection

@section('content')

    <!-- 登录表单 begin-->
    <div>
        <form id="loginFm" action="{{ url('user/doLogin') }}" method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            邮箱: <input type="text" name="email" id="email" value="{{ old('email')  }}" /><br />
            密码: <input type="password" minlength="6" maxlength="32" name="password" id="password"><br/>
            <input type="button" onclick="login()" value="登录" id="loginBtn" />
        </form>

    </div>
    <!-- 登录表单 end-->

    <!-- 错误信息 begin-->
    <div class="alert alert-danger">
        @if(isset($errors))
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif
    </div>
    <!-- 错误信息 end -->
@endsection

@section('script')

<script>
    /**
     * @returns {boolean}
     */
    function login() {

        if (false == checkForm()) {
            return false;
        }
        return $('#loginFm').submit();

        if ($('#loginBtn').val() == "登录") {
            $('#loginBtn').val('正在登录');
            $.ajax({
                type: "POST",
                url: "<?php echo url('user/login');?>",
                data: $('#loginFm').serialize(),
                dataType: "json",
                success: function (msg) {
                    alert(msg.message);
                    if (msg.code && msg.code == '0'){
                        return window.location.href = msg.redirectUrl;
                    }
                }
            });

            $('#loginBtn').val('登录');
        }
    }
    /**
     * 检查form
     * @returns {boolean}
     */
    function checkForm() {
        // email
        var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
        if(!myreg.test($('#email').val())){
            alert('提示\n\n请输入有效的E_mail！');
            $('#email').focus();
            return false;
        }

        if (!$('#password').val()){
            alert('提示\n\n请输入有效的密码！');
            $('#password').focus();
            return false;
        }

       return true;
    }
</script>

@endsection