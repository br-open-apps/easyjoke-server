{{-- resources/views/emails/password.blade.php --}}

<a href="{{ url('password/reset/'.$token) }}">{{ url('password/reset/'.$token) }}</a>