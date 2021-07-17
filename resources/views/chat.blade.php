@section('content')

        <div class="active_field">
            <div class="chat">
                <div class="pinned{{""}}@if(!$chat_data['pinned']) hidden @endif" data-pinned="@if($chat_data['pinned']){{ $chat_data['pinned']->id }}@endif">
                        <p class="pin_t">Закрепленное сообщение <a href="#">Открепить</a></p>
                        <div class="mes">
                            <span class="user">@if($chat_data['pinned']){{ $chat_data['pinned']->nickname }}@endif</span>
                            <span class="message">: @if($chat_data['pinned']){{ $chat_data['pinned']->message }}@endif</span>
                            <p class="date">
                                @if($chat_data['pinned']){{ $chat_data['pinned']->date }}@endif
                            </p>
                        </div>
                    </div>
                <div class="input_field">
                    <textarea class="message_input" placeholder="Ваше сообщение..."></textarea>
                    <div class="chat_buttons">
                        <button class="button medium cancelButton closed">Отмена</button>
                        <button class="button medium sendMessage">Отправить</button>
                    </div>
                </div>
                <div class="messages_field">
                    @foreach($chat_data['messages'] as $message)
                        <div id="{{ $message->id }}" class="msg_block{{""}}@if($message->my_mes) my_message{{""}}@endif">
                            <div class="msg" style="background: {{ $message->color }}">
                                <div class="buttons">
                                    @if($message->can_modify)
                                        <span class="edit">✎</span>
                                        <span class="delete">🗑</span>
                                    @endif
                                    <span class="pin">📌</span>
                                </div>
                                <span class="like{{""}}@if(!$message->likes_qty) hidden{{""}}@endif">❤@if($message->likes_qty)<span class="qty filled{{""}}@if ($message->my_like) my @endif">{{ $message->likes_qty }}</span>@else<span class="qty"></span>@endif</span>
                                <span class="msg_nick">{{ $message->nickname }}</span>
                                <span class="msgMessage">{{ $message->message }}</span>
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
