@extends('layouts.main_template')
@section('title')
Админ панель - Отчеты
@endsection
@section('content')
<h3>Отчеты</h3>
<form method="post" action="/admin/reports">
	@csrf
	@include('layouts.validate')
	<div class="form-group">
		<label>Новости <input name="news" type="checkbox"></label>
	</div>
	<div class="form-group">
		<label>Статьи <input name="posts" type="checkbox"></label>
	</div>
	<div class="form-group">
		<label>Комментарии <input name="comments" type="checkbox"></label>
	</div>
	<div class="form-group">
		<label>Теги <input name="tags" type="checkbox"></label>
	</div>
	<div class="form-group">
		<label>Пользователей <input name="users" type="checkbox"></label>
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Создать отчет">
	</div>
</form>
@endsection
