@extends('layouts.app')

@section('content')

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
            <form method="post"> {{-- action="{{ route('blog.admin.posts.destroy', $item->id) }}" --}}
                @csrf
                <label for="nickname">Никнейм:</label>
                <input type="text" name="nickname" />
                <label for="email">Email:</label>
                <input type="text" name="email" />
                <label for="email">Пароль:</label>
                <input type="password" name="password" />
                <input type="submit" class="submit inverted submit_auth" value="В чат" />
            </form>
        </div>
    </div>

@endsection
