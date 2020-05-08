@extends('layouts.main_template')
@section('title')
Статистика
@endsection
@section('content')
  <div>
	  <h2>Статистика</h2>
	  <p>Общее количество статей {{$countPosts}}</p>
	  <p>Общее количество новостей {{$countNews}}</p>
	  <p>Автор с наибольшим числом постов {{$morePostsAutor}}</p>
	  <p>Самая длинная статья <a href="/posts/{{$slugLongestPost}}">{{$nameLongestPost}}</a></p>
	  <p>Самая короткая статья <a href="/posts/{{$slugShortestPost}}">{{$nameShortestPost}}</a></p>
	  <p>Средние количество статей у “активных” пользователей {{$avgPosts}}</p>
	  <p>Самая непостоянная статья <a href="/posts/{{$changablePostSlug}}">{{$changablePostName}}</a></p>
	  <p>Самая обсуждаемая статья <a href="/posts/{{$mostCommentablePostSlug}}">{{$mostCommentablePostName}}</a></p>
  </div>
@endsection
