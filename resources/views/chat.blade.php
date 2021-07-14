@section('content')

    <div class="chat_container">
        <div class="left_column">
            <div class="left_column_top">
                <input type="hidden" value="{{ $user->color }}" class="userColor" />
                <p class="nickname">Вы вошли как: <span class="user_nickname"><span class="my_nickname" style="color: {{ $user->color }}">{{ $user->nickname }}</span></span></p>
                <button class="button medium">Настройки</button>
            </div>
            <div class="left_column_group">
                <p class="left_column_group_heading">Действия</p>
                <a href="#">Комнаты</a>
            </div>
            @if($user->is_admin)
                <div class="left_column_group">
                    <p class="left_column_group_heading">Администратор</p>
                    <a href="#">Пользователи</a>
                    <a href="#">Редакторы</a>
                    <a href="#">Фильтры</a>
                    <a href="#">Чат</a>
                </div>
            @endif
            @if($user->is_editor)
                <div class="left_column_group">
                    <p class="left_column_group_heading">Редактор</p>
                    <a href="#">Пользователи</a>
                    <a href="#">Фильтры</a>
                    <a href="#">Чат</a>
                </div>
            @endif
        </div>
        <div class="active_field">
            <div class="chat">
                <div class="input_field">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <textarea class="message_input" placeholder="Ваше сообщение..."></textarea>
                    <button class="button medium sendMessage">Отправить</button>
                </div>
                <div class="messages_field">
                    @foreach($messages as $message)
                        <div @if ($message->nickname == \Illuminate\Support\Facades\Auth::user()->nickname) class="my_message" @endif>
                            <div class="msg" style="background: {{ $message->color }}">
                                <span class="msg_nick">{{ $message->nickname }}</span>
                                {{ $message->message }}
                                <span class="datetime">{{ $message->date }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="tic_tac_toe">
                <p>Tic Tac Toe</p>
            </div>
        </div>
    </div>

@endsection

@include('layouts.app')
