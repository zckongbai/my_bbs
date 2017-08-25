@extends('layouts.main')

@section('title', '注册页面')

@section('sidebar')
    @parent

@endsection

@section('content')
    <!-- 注册 begin -->
    <div>
        <p>注册。</p>

        <form id="registerFm" action="{{ url('user/doRegister') }}" method="post">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            名称: <input type="text" name="name" id="name" value="{{ old('name') }}" /><br />
            邮箱: <input type="email" name="email" id="email" value="{{ old('email') }}" /><br />
            密码: <input type="password" minlength="6" maxlength="32" name="password" id="password"><br/>
            确认密码: <input type="password" minlength="6" maxlength="32" name="surePassword" id="surePassword"><br/>
            <input type="button" onclick="register()" value="注册" id="registerBtn" />
        </form>
    </div>
    <!-- 注册 end -->

    <!-- 错误信息 begin-->
    @include('common.errors')
    <!-- 错误信息 end -->

@endsection

@section('script')
<script>

    /**
     * 注册
     */
    function register() {

        if (false == checkForm()) {
            return false;
        }

        if ($('#registerBtn').val() == "注册") {

            $('#registerFm').submit();
            return ;

            $('#registerBtn').val('正在注册');
            $.ajax({
                type: "POST",
                url: "<?php echo url('user/register');?>",
                data: $('#registerFm').serialize(),
                dataType: "json",
                success: function (msg) {
//                    console.log(msg);
                    alert(msg.message);
                    if (msg.code && msg.code == '0'){
                        window.location.href = msg.redirectUrl;
                    }
                }
            });

            $('#registerBtn').val('注册');
        }
    }

    /**
     * 检查form
     * @returns {boolean}
     */
    function checkForm() {

        if (!$('#name').val()){
            alert('提示\n\n请输入有效的用户名！');
            $('#name').focus();
            return false;
        }

        // email
        var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
        if (!myreg.test($('#email').val())){
            alert('提示\n\n请输入有效的E_mail！');
            $('#email').focus();
            return false;
        }

        if (!$('#password').val()){
            alert('提示\n\n请输入有效的密码！');
            $('#password').focus();
            return false;
        }

        if ($('#surePassword').val() !== $('#password').val()){
            alert('提示\n\n两次密码不一致！');
            $('#surePassword').focus();
            return false;
        }

        return true;
    }
</script>
@endsection
