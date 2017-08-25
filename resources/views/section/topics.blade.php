@extends('layouts.main')

@section('title', '板块帖子')

@section('sidebar')

    @parent

@endsection


@section('content')

    <!-- 列表 begin -->
    <div>
        <table>
            <tr>
                <td>标题</td>
                <td>发表时间</td>
                <td>点击数</td>
                <td>回复数</td>
            </tr>
            @forelse($topics as $topic)
                <tr>
                    <td><a href="{{ url('topic',['id'=>$topic->id]) }}">{{ $topic->title }}</a></td>
                    <td>{{ $topic->create_at }}</td>
                    <td>{{ $topic->click_number }}</td>
                    <td>{{ $topic->reply_number }}</td>
                </tr>
            @empty
                <h4>暂无发帖</h4>
                <a href="{{ url('topic/add') }}">去发帖</a>
            @endforelse
        </table>

    </div>
    <!-- 列表 end -->

    <!-- 错误信息 begin-->
    @include('common.errors')
    <!-- 错误信息 end -->


@endsection

