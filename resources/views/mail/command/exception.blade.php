@component('mail::message')
# {{ __('Exception occurred during command', ['command' => $command]) }}

{{ __('Data has been transferred till id:', ['id' => $id]) }}

{{ __('Error:', ['exception' => $exception]) }}


{{__('Thanks,')}}
{{ config('app.name') }}
@endcomponent
