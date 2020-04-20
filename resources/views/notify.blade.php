@extends('layouts.main_template')
@section('title')
Уведомление - статьи
@endsection
@section('content')
<h3>Статьи</h3>
	<h1>Опубликованы новые статьи</h1>
	@foreach($posts as $post)
	<h2>{{$post->name}}</h2>
	<p>{{$post->content}}</p>
	@endforeach
@endsection

