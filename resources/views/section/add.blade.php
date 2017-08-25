@extends('layouts.main')

@section('title', '板块添加')


@section('sidebar')
    @parent
@endsection

@section('content')

    <!-- 添加表单 begin -->
    <div>
        <form id="sectionAddFm"  action="<?php echo url('section/add');?>" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"><br />
            板块名称: <input type="name" id="secName" name="name" /><span id="inputNameError"></span><br />
            <input type="submit" value="添加" /><br />
        </form>
    </div>
    <!-- 添加表单 end -->


    <!-- 错误信息 begin-->
    @include('common.errors')
    <!-- 错误信息 end -->

@endsection

@section('script')

<script>
    $(function () {
        $('#sectionAddFm').submit(function () {
            if ($('#secName').val() == ''){
                $('#secName').focus();
                $('#inputNameError').html('不能为空')
                return false;
            }
            return true;
        })
    });

</script>

@endsection