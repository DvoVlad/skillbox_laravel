@extends('layouts.main_template')
@section('title')
Детальная страница - {{$title}}
@endsection
@section('content')
<h3>{{$post->name}}</h3>
<i>{{$post->created_at}}</i>
<p>{{$post->content}}</p>
<a href="/">На главную</a>
@endsection
