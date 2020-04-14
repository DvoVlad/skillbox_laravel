@component('mail::message')
# Introduction

Post created!
Name:{{$name}}
Link:{{$link}}

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
