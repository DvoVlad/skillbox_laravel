@extends('layouts.main_template')
@section('title')
Список статей - Админ панель
@endsection
@section('content')
<div class="row">
    <div class="col-md-8 blog-main">
      <h3 class="pb-4 mb-4 font-italic border-bottom">
        From the Firehose
      </h3>
      @include('layouts.validate')
	@if($news)
		@foreach($news as $new)
      <div class="blog-post">
        <h2 class="blog-post-title"><a href="posts/{{$post->slug}}">{{$new->name}}</a></h2>
        <p class="blog-post-meta">{{$new->created_at}}</p>
		<p>{{$new->anonce}}</p>
		<p>
			<b>Теги:</b>
			@foreach($post->tags as $tag)
				<a href="{{url('/tag/' . $tag->id . '/posts')}}" class="badge badge-secondary">{{$tag->name}}</a>
			@endforeach
		</p>
		<a href="{{url('/admin/news/' . $new->slug . '/edit')}}">Редактировать</a>
		<form method="post" action="{{url('/news/' . $new->slug)}}">@csrf @method("DELETE") <input type="submit" class="btn btn-danger" value="Удалить"></form>
      </div><!-- /.blog-post -->
		@endforeach
	@endif

      <nav class="blog-pagination">
        <a class="btn btn-outline-primary" href="#">Older</a>
        <a class="btn btn-outline-secondary disabled" href="#" tabindex="-1" aria-disabled="true">Newer</a>
      </nav>

    </div><!-- /.blog-main -->

    <aside class="col-md-4 blog-sidebar">
     <!-- Облако тегов -->
     <h3>Облако тегов</h3>
     @include('layouts.allTags')
    </aside><!-- /.blog-sidebar -->

  </div><!-- /.row -->
@endsection
