<div class="form-group">
		<input class="form-control" name="name" type="text" placeholder="Название" value="{{old('name',$post->name)}}">
	</div>
	<div class="form-group">
		<input class="form-control" name="slug" type="text" placeholder="Символьный код" value="{{old('slug', $post->slug)}}">
	</div>
	<div class="form-group">
		<label for="exampleFormControlTextarea1">Анонс</label>
		<textarea name="anonce" class="form-control" id="exampleFormControlTextarea1">{{old('anonce', $post->anonce)}}</textarea>
	</div>
	<div class="form-group">
		<label for="exampleFormControlTextarea2">Текст</label>
		<textarea name="content" class="form-control" id="exampleFormControlTextarea2">{{old('content', $post->content)}}</textarea>
	</div>
	<div class="form-group">
		<input class="form-check-input" {{($post->publish == 1) ? 'checked' : ''}} type="checkbox" name="publish" value="1">
		<label class="form-check-label" for="inlineCheckbox1">Публиковать</label>
	</div>
	<div class="form-group">
		@if($update)
			<p>Сейчас привязана к тегам @foreach($post->tags as $tag)<a class="badge badge-secondary" href="/tag/{{$tag->id}}/posts">{{$tag->name}}</a>@endforeach</p>
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
