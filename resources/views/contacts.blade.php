@extends('layouts.main_template')
@section('title')
Контакты
@endsection
@section('content')
<div>
	<p>Приветствую вас на странице контакты</p>
</div>
<h3>Форма обратной связи</h3>
<form id="create-form" action="/contacts" method="post">
	@csrf
	@include('layouts.validate')
	<div class="form-group">
		<input class="form-control" name="email" type="email" placeholder="Ваш email">
	</div>
	<div class="form-group">
		<label for="exampleFormControlTextarea2">Сообщение</label>
		<textarea name="message" class="form-control" id="exampleFormControlTextarea2"></textarea>
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Отправить">
	</div>
</form>
@endsection
