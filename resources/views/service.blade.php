@extends('layouts.main_template')
@section('title')
Отправка уведомления
@endsection
@section('content')
<h3>Отправить уведомление</h3>
<form id="create-form" action="/service" method="post">
	@csrf
	@include('layouts.validate')
	<div class="form-group">
		<input class="form-control" name="title" type="text" placeholder="Заголовок уведомления">
	</div>
	<div class="form-group">
		<textarea class="form-control" name="text" placeholder="Текст уведомления"></textarea>
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Отправить уведомление">
	</div>
</form>
@endsection
