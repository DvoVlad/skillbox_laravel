@extends('layouts.main_template')
@section('title')
Редактирование статьи
@endsection
@section('content')
<h3>Редактировать статью</h3>
<form id="create-form" action="/posts/{{$post->slug}}" method="post">
	@method("PATCH")
	@csrf
	@include('layouts.validate')
	@include('post.fields', ['update' => true, 'post' => $post])
	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Редактировать статью">
	</div>
</form>
@endsection
