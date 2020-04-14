@component('mail::message')
# Introduction

Post {{$name}} deleted!

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
