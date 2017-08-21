@extends('layouts.main')

@section('title', '首页')

@section('sidebar')

    @parent

@endsection

@section('content')
    <h1>首页</h1>

    @if(count($sections))
        <!-- 类别top begin-->
        <div>
            <dl>
                @foreach($sections as $section)
                <dt><a href="#">{{ $section['name'] }}</a></dt>

                    @if($section->topics)
                        @foreach($section->topics->sortByDesc('click_number') as $topic)
                        <dd><a href="{{ url('topic', ['id' => $topic->id]) }}">标题:{{ $topic->title }}</a>浏览量:{{ $topic->click_number }}</dd>
                        @endforeach
                    @endif
                @endforeach

            </dl>
        </div>
        <!-- 类别top end -->
    @endif

@endsection