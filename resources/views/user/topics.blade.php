@extends('layouts.main')

@section('title', '我的发帖')

@section('sidebar')
    @parent

    @include('layouts.user')

@endsection

@section('content')

    @if(count($topics) > 0)
        <div>
            <table>
                <tr>
                    <th>标题</th>
                    <th>浏览量</th>
                    <th>创建时间</th>
                    <th>删除</th>
                </tr>
                @foreach($topics as $topic)
                <tr>
                    <td><a href="{{ url('topic', ['id'=>$topic->id]) }}">{{ $topic->title }}</a></td>
                    <td>{{ $topic->click_number }}</td>
                    <td>{{ $topic->created_at }}</td>
                    <td><a href="{{ route('topic/{id}/delete', ['id'=>$topic->id,'backUrl'=>url('user/topics')]) }}" onclick="return confirm('确认删除?');">删除</a></td>
                </tr>
                @endforeach
            </table>
        </div>

        <!-- 分页 begin -->
        <?php echo $topics->render(); ?>
        <!-- 分页 end -->

    @else
        <h4>暂无发帖</h4>
    @endif

@endsection


