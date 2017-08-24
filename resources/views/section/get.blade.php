@extends('layouts.main')

@section('title', '板块查看')

@section('sidebar')

    @parent

@endsection


@section('content')

    <!-- 板块 begin -->
    <div>
        <form id="updateFm" action="{{ route('section/update', ['id'=>$section->id]) }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"><br />
            板块名称: <input type="name" id="secName" name="name" value="{{ $section->name }}" /><span id="inputNameError"></span><br />
            <input type="button" value="返回列表" onclick="return window.location.href='{{ url('section') }}'" /><br />
            <input type="submit" value="修改" /><br />
        </form>

    </div>
    <!-- 板块 end -->

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