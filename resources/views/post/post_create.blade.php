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
	<div class="form-group">
		<input class="form-control" name="name" type="text" placeholder="Название" value="{{old('name')}}">
	</div>
	<div class="form-group">
		<input class="form-control" name="slug" type="text" placeholder="Символьный код" value="{{old('slug')}}">
	</div>
	<div class="form-group">
		<label for="exampleFormControlTextarea1">Анонс</label>
		<textarea name="anonce" class="form-control" id="exampleFormControlTextarea1">{{old('anonce')}}</textarea>
	</div>
	<div class="form-group">
		<label for="exampleFormControlTextarea2">Текст</label>
		<textarea name="content" class="form-control" id="exampleFormControlTextarea2">{{old('content')}}</textarea>
	</div>
	<div class="form-group">
		<input class="form-check-input" type="checkbox" name="publish" value="1">
		<label class="form-check-label" for="inlineCheckbox1">Публиковать</label>
	</div>
	<div class="form-group">
		<label>
			<p>Теги:</p>
			<select multiple name="tags[]">
			@if($tags)
				@foreach($tags as $tag)
					<option value="{{$tag->id}}">{{$tag->name}}</option>
				@endforeach
			@endif
			</select>
		</label>
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Создать статью">
	</div>
</form>
@endsection
