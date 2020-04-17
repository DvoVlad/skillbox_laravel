@extends('layouts.main_template')
@section('title')
Создание тега
@endsection
@section('content')
<h3>Создать тег</h3>
<form id="create-form" action="/tags/create" method="post">
	@csrf
	@include('layouts.validate')
	<div class="form-group">
		<input class="form-control" name="name" type="text" placeholder="Название">
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Создать тег">
	</div>
</form>
@endsection
