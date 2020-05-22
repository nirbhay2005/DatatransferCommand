@component('mail::message')
# {{ __('Command Successful', ['command' => $commandName]) }}

{{ __('Data has been transferred till id:', ['id' => $lastId]) }}

    {{__('Thanks,')}}
    {{ config('app.name') }}
@endcomponent

