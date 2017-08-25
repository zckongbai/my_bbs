@extends('layouts.main')

@section('title', '帖子详情')

@section('sidebar')
    @parent

    帖子侧栏
@endsection

@section('content')
    <!-- 帖子内容begin -->
    <div>
        <u>帖子内容</u>
        <ul>
            <li>用户:<span>{{ $topic->user->name }}</span></li>
            <li>标题:<title>{{ $topic->title }}</title></li>
            <li>内容:<section>{{ $topic->content }}</section></li>
            <li>时间:<time>{{ $topic->created_at }}</time></li>
        </ul>
    </div>
    <!-- 帖子内容end -->

    @if(count($topic->replies) > 0)
    <!-- 回贴列表begin -->
    <div>
        <u>回复列表</u>
        <table>
            <tr>
                <td>用户名</td>
                <td>内容</td>
                <td>楼层</td>
                <td>时间</td>
            </tr>
            @foreach($topic->replies as $reply)
                <tr>
                    <td>{{ $reply->user->name }}</td>
                    <td>{{ $reply->content }}</td>
                    <td>{{ $reply->floor }}</td>
                    <td>{{ $reply->created_at }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    <!-- 回贴列表end -->
    @endif

    @if($topic->status == 1)
    <!-- 回复begin -->
    <div>
        <u>发表回复</u>
        <form action="{{ url('topic/reply') }}" method="post" onsubmit="return checkFm();">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="topic_id" value="{{ $topic->id }}">
            <textarea name="content" id="content" cols="30" rows="10"></textarea> <span id="conError"></span>
            <br>
            <input type="submit" value="提交">
        </form>
    </div>
    <!-- 回复end -->
    @endif

    <!-- 错误信息 begin-->
    @include('common.errors')
    <!-- 错误信息 end -->

@endsection

@section('script')
    <script>
        /**
         * 校验form
         * @returns {boolean}
         */
        function checkFm() {
            if (!$('#content').val()){
                $('#conError').html('内容不能为空');
                return false;
            }
            return true;

        }
    </script>
@endsection
