<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
        <link href="{{ asset('css/app.css') }}" type="text/css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/script.js') }}"></script>
        <script src="https://comet-server.ru/CometServerApi.js"></script>
        <script src="https://cdn.rawgit.com/JDMcKinstry/JavaScriptDateFormat/master/Date.format.min.js"></script>
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
                    <input type="submit" value="Выйти из чата" class="button right_button exitChat">
                </form>
            @endif
        </div>

        @if(Auth::check())

            <div class="chat_container">
            <div class="popup_back">
                <div class="popup">
                    <p>Вы действительно хотите удалить сообщение?</p>
                    <div class="button_group_popup">
                        <button class="button inverted medium popupCancel">Отмена</button>
                        <button class="button medium popupDelete">Удалить</button>
                    </div>
                </div>
            </div>

            <div class="left_column">
                <div class="left_column_top">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" value="{{ $user->color }}" class="userColor" />
                    <input type="hidden" value="{{ $user->id }}" class="userId" />
                    <p class="nickname">Вы вошли как: <span class="user_nickname"><span class="my_nickname" style="color: {{ $user->color }}">{{ $user->nickname }}</span></span></p>
                    <button class="button medium">Настройки</button>
                </div>
                <div class="left_column_group">
                    <p class="left_column_group_heading">Действия</p>
                    <a href="#">Комнаты</a>
                </div>
                @if($user->is_admin || $user->is_editor)
                    <div class="left_column_group">
                        <p class="left_column_group_heading">Управление</p>
                        <a href="{{ route('chat.settings.users') }}">Пользователи</a>
                        <a href="/settings/filters">Фильтры</a>
                        <a href="/settings/chat">Чат</a>
                    </div>
                @endif
            </div>
        @endif

        @yield('content')

        @if(Auth::check())
            </div>
        @endif

    </body>
</html>
