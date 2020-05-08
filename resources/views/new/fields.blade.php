<div class="form-group">
		<input class="form-control" name="name" type="text" placeholder="Название" value="{{old('name',$new->name)}}">
	</div>
	<div class="form-group">
		<input class="form-control" name="slug" type="text" placeholder="Символьный код" value="{{old('slug', $new->slug)}}">
	</div>
	<div class="form-group">
		<label for="exampleFormControlTextarea1">Анонс</label>
		<textarea name="anonce" class="form-control" id="exampleFormControlTextarea1">{{old('anonce', $new->anonce)}}</textarea>
	</div>
	<div class="form-group">
		<label for="exampleFormControlTextarea2">Текст</label>
		<textarea name="content" class="form-control" id="exampleFormControlTextarea2">{{old('content', $new->content)}}</textarea>
	</div>
	<div class="form-group">
		@if($update)
			<p>Сейчас привязана к тегам @foreach($new->tags as $tag)<a class="badge badge-secondary" href="/tag/{{$tag->id}}/posts">{{$tag->name}}</a>@endforeach</p>
		@endif
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
