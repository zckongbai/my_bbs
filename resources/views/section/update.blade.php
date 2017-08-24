@extends('layouts.main')

@section('title', '板块更新')

@section('sidebar')
    @parent

    板块的侧边栏
@endsection

@section('content')
    <div>
        <form action="{{ url('section/update') }}" method="post" onsubmit="return checkForm();">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" value="{{ $section->id }}"  >
            <input type="text" id="name" name="name" value="{{ $section->name }}">
            <input type="submit" value="更新">
        </form>
    </div>
@endsection

@section('script')
    <script>
        function checkForm() {
            if ($('#name').val() == ''){
                alert('名称不能为空');
                $('#name').force();
                return false;
            }
            return ture;
        }
    </script>

@endsection


