@component('mail::message')
{{ $author }}

{{ $text }}

@component('mail::button', ['url' => 'https://laravel.su/docs/8.x/mail#generating-mailables'])
{{ $time }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent