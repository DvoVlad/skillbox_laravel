@component('mail::message')
# Introduction

Post updated!
Name:{{$name}}
Link:{{$link}}

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
