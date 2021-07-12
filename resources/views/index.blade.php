@extends('layouts.app')

@section('content')

    @error('error')
        <div class="error_block">
            {{ $message }}
            <button class="close">
                <span>×</span>
            </button>
        </div>
    @enderror

    <div class="container">
        <div class="bc_about">
            <h1>BoguChat - новый, обновленный</h1>
            <p>BoguChat - усовершенствованная версия старого, безымянного, онлайн-чата.</p>
            <p>Основа данного чата - PHP-фреймворк Laravel. Был выбран как предмет практики, под руку попался старый, немытый чат, который как раз можно обновить, параллельно практикуясь.</p>
            <p>Все функции старого чата остались в новой версии - крестики-нолики в реальном времени, полноценно функционирующий чат с отправкой файлов (теперь их можно отправить несколько в одном сообщении), общение с ботом в чате.</p>
            <p>Из новых возможностей - создание комнат для нескольких отдельных членов чата, мультиязычность, кастомизация пользователя и многое другое.</p>
        </div>
        <div class="bc_auth">
            <p class="bc_heading2 text_center">Зарегистрироваться или выполнить вход</p>
            <form method="POST" action="{{ route('chat.auth') }}">
                @csrf
                <div class="input_block">
                    <label for="nickname">Никнейм:</label>
                    @error('nickname')
                        <p class="error">{{ $message }}</p>
                    @enderror
                    <input type="text" name="nickname" value="{{ old('nickname') }}" />
                </div>
                <div class="input_block">
                    <label for="email">Email:</label>
                    @error('email')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    <input type="text" name="email" value="{{ old('email') }}" />
                </div>
                <div class="input_block">
                    <label for="email">Пароль:</label>
                    @error('password')
                    <p class="error">{{ $message }}</p>
                    @enderror
                    <input type="password" name="password" />
                </div>
                <input type="submit" class="submit inverted submit_auth" value="В чат" />
            </form>
        </div>
    </div>

@endsection
