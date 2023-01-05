@component('mail::message')
    {{ $details['title'] }}

    {{ $details['body'] }}

    {{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

    Thanks
    {{ config('app.name') }}
@endcomponent
