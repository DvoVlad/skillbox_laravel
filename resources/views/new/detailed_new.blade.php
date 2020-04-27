@extends('layouts.main_template')
@section('title')
Детальная страница новости - {{$title}}
@endsection
@section('content')
<h3>{{$new->name}}</h3>
<i>{{$new->created_at}}</i>
<p>{{$new->content}}</p>
<a href="/news">К списку</a>
@if($new->comments)
@foreach($new->comments as $comment)
<div class="comment"><p>{{$comment->user->name}}</p><p>{{$comment->message}}</p></div>
@endforeach
@endif
@auth
<h4>Оставить комментарий</h4>
<hr>
<form action="/news/comment" method="post">
	@csrf
	@include('layouts.validate')
	<p>{{auth()->user()->name}}</p>
	<input type="hidden" name="user_id" value="{{auth()->user()->id}}">
	<input type="hidden" name="commentable_id" value="{{$new->id}}">
	<div class="form-group">
		<textarea name="message"></textarea>
	</div>
	<div class="form-group">
		<input type="submit" value="Отправить комментарий">
	</div>
</form>
@endauth
@endsection
