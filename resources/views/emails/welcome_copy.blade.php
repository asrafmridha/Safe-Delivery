@component('mail::message')
# Introduction
# Hello from Flier Express {{$user['email']}}

The body of your message.

@component('mail::button', ['url' => route('frontend.about')])
Confirm Link
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
