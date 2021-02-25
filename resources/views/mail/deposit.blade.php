@component('mail::message')
# Deposit of NGN{{$deposit->amount}} was successful

Hello, {{$deposit->user->username}}, you have successfully funded your wallet with <b>NGN{{$deposit->amount}}</b>. Thanks for trusting us.


Thanks,<br>
{{ config('app.name') }}
@endcomponent
