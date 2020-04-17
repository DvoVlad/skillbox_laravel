@extends('layouts.main_template')
@section('title')
Создание статьи
@endsection
@section('content')
<h3>Создать статью</h3>
<form id="create-form" action="/posts" method="post">
	@csrf
	@include('layouts.validate')
	<div class="form-group">
		<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
	</div>
	@include('post.fields', ['update' => false, 'post' => new App\Post()])
	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Создать статью">
	</div>
</form>
@endsection
