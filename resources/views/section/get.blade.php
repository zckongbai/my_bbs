@extends('layouts.main')

@section('title', '板块查看')

@section('sidebar')

    @parent

@endsection


@section('content')

    <!-- 板块 begin -->
    <div>
        <form id="updateFm" action="{{ route('section/update') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"><br />
            <input type="hidden" name="id" value="{{ $section->id }}">
            板块名称: <input type="name" id="secName" name="name" value="{{ $section->name }}" /><span id="inputNameError"></span><br />
            <input type="button" value="返回列表" onclick="return window.location.href='{{ url('section') }}'" /><br />
            <input type="button" value="删除" onclick="return window.location.href='{{ route('section/delete', ['id'=>$section->id]) }}'" /><br />
            <input type="submit" value="修改" /><br />
        </form>

    </div>
    <!-- 板块 end -->


    <!-- 错误信息 begin-->
    @include('common.errors')
    <!-- 错误信息 end -->

@endsection


@section('script')

    <script>
        $(function () {
            $('#updateFm').submit(function () {
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