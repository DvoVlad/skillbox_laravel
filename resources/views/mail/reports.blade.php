@component('mail::message')
# Introduction

Отчет
@if($postCount)
Количество статей: {{$postCount}}
@endif
@if($newsCount)
Количество новостей: {{$newsCount}}
@endif
@if($commentCount)
Количество тегов: {{$tagCount}}
@endif
@if($userCount)
Количество пользователей: {{$userCount}}
@endif

@component('mail::button', ['url' => 'laravel.loc'])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
