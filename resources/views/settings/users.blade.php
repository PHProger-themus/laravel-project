@section('content')

    <div class="settings_block">
        <h1>Пользователи</h1>
        <table class="settings_table" border="0">
            <tr class="table_head">
                <td>Никнейм</td>
                <td>Email</td>
                <td>Статус</td>
                <td>Тип</td>
                <td>Был в сети</td>
                <td>Зарегистрирован</td>
                <td>Сообщений в чате</td>
                <td colspan="3">Действия</td>
            </tr>
            @foreach($users as $user_info)
                <tr class="tduser">
                    <td><span style="color: {{ $user_info->color }}">{{ $user_info->nickname }}</span></td>
                    <td>{{ $user_info->email }}</td>
                    <td><span style="color:@if($user_info->status == 'online') #009a00{{""}}@elseif($user_info->status == 'banned') red{{""}}@else #afafaf{{""}}@endif">{{ $user_info->status }}</span></td>
                    <td>@if($user_info->is_editor){{"Редактор"}}@elseif($user_info->is_admin){{"Администратор"}}@else{{"Типичный чел"}}@endif</td>
                    <td>{{ $user_info->last_online }}</td>
                    <td>{{ \Carbon\Carbon::parse($user_info->created_at)->format('d.m.y в H:i') }}</td>
                    <td>{{ $user_info->msg_count }}</td>
                    @if(!($user_info->is_admin || $user_info->id == \Illuminate\Support\Facades\Auth::id()))
                        <td><button class="button middle editUser" id="{{ $user_info->id }}">Изменить</button></td>
                        <td><button class="button middle blockUser" id="{{ $user_info->id }}">Заблокировать</button></td>
                        <td><button class="button middle deleteUser" id="{{ $user_info->id }}">Удалить</button></td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>

@endsection

@include('layouts.app')
