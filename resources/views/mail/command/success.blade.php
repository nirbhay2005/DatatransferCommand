@component('mail::message')
    # {{ __('Command Successful', ['command' => $command]) }}

    {{ __('Data has been transferred till id:', ['id' => $id]) }}

    {{__('Thanks,')}}
    {{ config('app.name') }}
@endcomponent
