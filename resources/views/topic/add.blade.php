@extends('layouts.main')

@section('title', '发帖')

@section('sidebar')
    @parent

    @include('layouts.user')

@endsection

@section('content')
    <!-- 板块添加form begin -->
    <div>
        <form id="topicAddFm" action="<?php echo url('topic/doAdd');?>" method="POST">

            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

            板块:
            <select name="section_id">
                @foreach ($sections as $section)
                    <option @if ($sections->first() == $section) selected @endif value="{{ $section['id'] }}">{{ $section['name'] }}</option>
                @endforeach
            </select><br /><br />

            标题: <input type="text" name="title" id="title" value="{{ old('title') }}" /><br /><br />

            内容: <textarea name="content" id="content" rows="10" cols="30">{{ old('content') }}</textarea><br /><br />

            <input type="submit" value="发帖" onclick="return checkForm();"/>

        </form>
    </div>
    <!-- 板块添加form end -->


    <!-- 错误信息 begin-->
    @include('common.errors')
    <!-- 错误信息 end -->


@endsection

@section('script')

<script>
    /**
     * 检查表单字段
     */
    function checkForm() {

        if (!$('#title').val()){
            alert('标题不能为空');
            $('#title').focus();
            return false;
        }

        if ($('#content').val() == ''){
            alert('内容不能为空');
            $('#content').focus();
            return false;
        }

        $('#topicAddFm').submit();
    }

</script>

@endsection
