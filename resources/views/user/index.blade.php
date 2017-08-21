@extends('layouts.main')

@section('title', '用户中心')

@section('sidebar')
    @parent

    @include('layouts.user')

@endsection

@section('content')
    <p>用户中心</p>
@endsection


