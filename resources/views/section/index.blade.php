@extends('layouts.main')

@section('title', '板块列表')

@section('sidebar')

    @parent

@endsection


@section('content')

    <!-- 列表 begin -->
    <div>
        @forelse($sections as $section)
            <li><span>{{ $section->name }}</span><a href="{{ url('section',['id'=>$section->id]) }}">查看</a></li>
        @empty
            <h4>暂无板块</h4>
                <a href="{{ url('section/add') }}">去添加</a>
        @endforelse

    </div>
    <!-- 列表 end -->


    <!-- 错误信息 begin-->
    @include('common.errors')
    <!-- 错误信息 end -->

@endsection

