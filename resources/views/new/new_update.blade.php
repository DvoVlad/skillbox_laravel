@extends('layouts.main_template')
@section('title')
Редактирование новости - Админ панель
@endsection
@section('content')
<h3>Редактировать новость</h3>
<form id="create-form" action="/news/{{$new->slug}}" method="post">
	@method("PATCH")
	@csrf
	@include('layouts.validate')
	@include('new.fields', ['update' => true, 'new' => $new])
	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Редактировать новость">
	</div>
</form>
@endsection
