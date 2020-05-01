@extends('layouts.main_template')
@section('title')
Детальная страница - {{$title}}
@endsection
@section('content')
<h3>{{$post->name}}</h3>
<i>{{$post->created_at}}</i>
<p>{{$post->content}}</p>
<a href="/">На главную</a>
@if($post->comments)
@foreach($post->comments as $comment)
<div class="comment"><p>{{$comment->user->name}}</p><p>{{$comment->message}}</p></div>
@endforeach
@endif
@auth
<h4>Оставить комментарий</h4>
<hr>
<form action="/post/comment" method="post">
	@csrf
	@include('layouts.validate')
	<p>{{auth()->user()->name}}</p>
	<input type="hidden" name="user_id" value="{{auth()->user()->id}}">
	<input type="hidden" name="commentable_id" value="{{$post->id}}">
	<div class="form-group">
		<textarea name="message"></textarea>
	</div>
	<div class="form-group">
		<input type="submit" value="Отправить комментарий">
	</div>
</form>
@endauth
<hr>
@forelse($post->history as $item)
	<p>{{$item->email}} - {{$item->pivot->created_at->diffForHumans() }} - {{$item->pivot->before}} - {{$item->pivot->after}}</p>
@empty
	<p>Нет истории изменений</p>
@endforelse
@endsection
