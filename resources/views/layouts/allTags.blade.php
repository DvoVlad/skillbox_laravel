@if($tags)
		@foreach($tags as $tag)
		<a href="/tag/{{$tag->id}}/posts" class="badge badge-secondary">{{$tag->name}}</a>
		@endforeach
@endif
