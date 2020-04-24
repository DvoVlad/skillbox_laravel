@extends('layouts.main_template')
@section('title')
Создание новости
@endsection
@section('content')
<h3>Создать новость</h3>
<form id="create-form" action="/news" method="post">
	@csrf
	@include('layouts.validate')
	<div class="form-group">
		<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
	</div>
	@include('new.fields', ['update' => false, 'new' => new App\News()])
	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Создать новость">
	</div>
</form>
@endsection
