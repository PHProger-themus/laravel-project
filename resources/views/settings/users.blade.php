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
                <tr class="truser" id="{{ $user_info->id }}">
                    <td><span class="user_nickname" style="color: {{ $user_info->color }}">{{ $user_info->nickname }}</span></td>
                    <td>{{ $user_info->email }}</td>
                    <td class="status"><span style="color:@if($user_info->status == 'online') #009a00{{""}}@elseif($user_info->status == 'banned') red{{""}}@else #afafaf{{""}}@endif">{{ $user_info->status }}</span></td>
                    <td>@if($user_info->is_editor){{"Редактор"}}@elseif($user_info->is_admin){{"Администратор"}}@else{{"Типичный чел"}}@endif</td>
                    <td>{{ $user_info->last_online }}</td>
                    <td>{{ \Carbon\Carbon::parse($user_info->created_at)->format('d.m.y в H:i') }}</td>
                    <td>{{ $user_info->msg_count }}</td>
                    @if(!($user_info->is_admin || $user_info->id == $user->id))
                        <td><a href="{{ route('chat.settings.edit-user', ['id' => $user_info->id]) }}" class="button middle editUser">Изменить</a></td>
                        @if($user->is_admin || ($user->is_editor && $user->canBlockUsers))<td><button class="button middle blockUser">@if($user_info->status == 'banned')Разблокировать@elseЗаблокировать@endif</button></td>@endif
                        @if($user->is_admin || ($user->is_editor && $user->canDeleteUsers))<td><button class="button middle deleteUser">Удалить</button></td>@endif
                    @endif
                </tr>
            @endforeach
        </table>
    </div>

@endsection

@include('layouts.app')
