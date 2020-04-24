@extends('layouts.main_template')
@section('title')
Детальная страница новости - {{$title}}
@endsection
@section('content')
<h3>{{$new->name}}</h3>
<i>{{$new->created_at}}</i>
<p>{{$new->content}}</p>
<a href="/news">К списку</a>
@endsection
