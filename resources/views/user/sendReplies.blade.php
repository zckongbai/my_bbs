@extends('layouts.main')

@section('title', '收到的回复')

@section('sidebar')
    @parent

    @include('layouts.user')

@endsection

@section('content')
    <p>发出的回复</p>

    @if(count($replies))
        <table border="1">
            <tr>
                <th>帖子标题</th>
                <th>回复内容</th>
                <th>楼层</th>
                <th>回复时间</th>
            </tr>
            @foreach($replies as $reply)
                <tr>
                    <td><a href="{{ url('topic', ['id'=>$reply->topic_id]) }}">{{ $reply->topic_title }}</a></td>
                    <td><a href="{{ url('topic', ['id'=>$reply->topic_id]) }}">{{ $reply->content }}</a></td>
                    <td><a href="{{ url('topic', ['id'=>$reply->topic_id]) }}">{{ $reply->floor }}</a></td>
                    <td><a href="{{ url('topic', ['id'=>$reply->topic_id]) }}">{{ $reply->created_at }}</a></td>
                </tr>
            @endforeach
        </table>
    @endif

    <!-- 分页 begin -->
    <?php echo $replies->render(); ?>
    <!-- 分页 end -->

@endsection


