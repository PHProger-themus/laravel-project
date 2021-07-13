@section('content')

    @if($errors->auth->count() || $errors->register->count())
        <div class="error_block">
            @if($errors->auth->count())
                {{ $errors->auth->first() }}
            @elseif($errors->register->count())
                {{ $errors->register->first() }}
            @endif
            <button class="close">
                <span>×</span>
            </button>
        </div>
    @endif



    <div class="container">
        <div class="bc_about">
            <h1>BoguChat - новый, обновленный</h1>
            <p>BoguChat - усовершенствованная версия старого, безымянного, онлайн-чата.</p>
            <p>Основа данного чата - PHP-фреймворк Laravel. Был выбран как предмет практики, под руку попался старый, немытый чат, который как раз можно обновить, параллельно практикуясь.</p>
            <p>Все функции старого чата остались в новой версии - крестики-нолики в реальном времени, полноценно функционирующий чат с отправкой файлов (теперь их можно отправить несколько в одном сообщении), общение с ботом в чате.</p>
            <p>Из новых возможностей - создание комнат для нескольких отдельных членов чата, мультиязычность, кастомизация пользователя и многое другое.</p>
        </div>
        <div class="bc_auth">
            <div class="block_switch_group">
                <p class="block_switch @if(!session('form')) switch_visible @endif" data-block="block_auth_form">Вход</p>
                <p class="block_switch @if(session('form')) switch_visible @endif" data-block="block_reg_form">Регистрация</p>
            </div>
            <div class="block block_auth_form @if(!session('form')) block_visible @endif">
                <p class="bc_heading2 text_center">Вход в BoguChat</p>
                <form method="POST" action="{{ route('chat.auth') }}">
                    @csrf
                    <div class="input_block">
                        <label for="nickname">Никнейм:</label>
                        @error('nickname', 'auth')
                            <p class="error">{{ $message }}</p>
                        @enderror
                        <input type="text" name="nickname" value="@if($errors->auth->count()){{ old('nickname') }}@endif" />
                    </div>
                    <div class="input_block">
                        <label for="email">Пароль:</label>
                        @error('password', 'auth')
                        <p class="error">{{ $message }}</p>
                        @enderror
                        <input type="password" name="password" />
                    </div>
                    <input type="submit" class="submit inverted submit_auth" value="В чат" />
                </form>
            </div>
            <div class="block block_reg_form @if(session('form')) block_visible @endif">
                <p class="bc_heading2 text_center">Регистрация в BoguChat</p>
                <form method="POST" action="{{ route('chat.reg') }}">
                    @csrf
                    <div class="input_block">
                        <label for="nickname">Никнейм:</label>
                        @error('nickname', 'register')
                        <p class="error">{{ $message }}</p>
                        @enderror
                        <input type="text" name="nickname" value="@if($errors->register->count()){{ old('nickname') }}@endif" />
                    </div>
                    <div class="input_block">
                        <label for="email">Email:</label>
                        @error('email', 'register')
                        <p class="error">{{ $message }}</p>
                        @enderror
                        <input type="text" name="email" value="@if($errors->register->count()){{ old('email') }}@endif" />
                    </div>
                    <div class="input_block">
                        <label for="email">Пароль:</label>
                        @error('password', 'register')
                        <p class="error">{{ $message }}</p>
                        @enderror
                        <input type="password" name="password" />
                    </div>
                    <input type="submit" class="submit inverted submit_auth" value="В чат" />
                </form>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.app')
