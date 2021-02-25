@component('mail::message')
# Welcome To Our Platform

Hello {{$user->username}}, we are pleased to welcome you to our platform. Kindly update your profile to enjoy all our financial benefits


Thanks,<br>
{{ config('app.name') }}
@endcomponent
