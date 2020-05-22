@component('mail::message')
    # {{ __('Exception occurred during command', ['command' => $commandName]) }}

    {{ __('Data has been transferred till id:', ['id' => $lastId]) }}

    {{ __('Error:', ['exception' => $exception]) }}


    {{__('Thanks,')}}
    {{ config('app.name') }}
@endcomponent
