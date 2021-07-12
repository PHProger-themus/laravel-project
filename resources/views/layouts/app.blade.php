<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
        <link href="{{ asset('css/app.css') }}" type="text/css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700&display=swap" rel="stylesheet">
    </head>
    <body>

        <div class="header">
            <p class="logo">BoguChat</p>
            @if(Auth::check())
                <form method="POST" action="{{ route('chat.logout') }}">
                    @csrf
                    <input type="submit" value="Выйти из чата" class="button right_button">
                </form>
            @endif
        </div>

        @yield('content')

    </body>
</html>
