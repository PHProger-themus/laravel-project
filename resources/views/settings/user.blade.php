@section('content')

    <div class="settings_block">
        <h1>{{ $userChange->nickname }}<span class="status_user" style="color:@if($userChange->status == 'online') #009a00{{""}}@elseif($userChange->status == 'banned') red{{""}}@else #afafaf{{""}}@endif">{{ $userChange->status }}</span></h1>
        <form method="POST" action="{{ route('chat.settings.modify-user') }}">
        @csrf
            <input type="hidden" name="id" class="edit_id" value="{{ $userChangeId }}" />
            <div class="infoBlock">
                <div class="main_info">
                        <div class="inlineInput_line">
                            <label for="nickname">Никнейм</label>
                            @error('nickname')
                                <p class="error">{{ $message }}</p>
                            @enderror
                            <input type="text" name="nickname" class="edit_nickname" value="{{ old('nickname', $userChange->nickname) }}" />
                        </div>
                        <div class="inlineInput_line">
                            <label for="nickname">Email</label>
                            @error('email')
                                <p class="error">{{ $message }}</p>
                            @enderror
                            <input type="text" name="email" value="{{ old('email', $userChange->email) }}" />
                        </div>
                        @if($user->is_admin)
                            <div class="inlineInput_line">
                                <input type="checkbox" name="is_admin" @if(old('is_admin', $userChange->is_admin))checked{{""}}@endif /><label class="inline_label" for="admin">Администратор</label>
                            </div>
                        @endif
                        <div class="inlineInput_line">
                            <input type="color" name="color" class="edit_color" value="{{ old('color', $userChange->color) }}"><label class="inline_label" for="color">Цвет никнейма</label>
                        </div>
                </div>
                @if(!($user->is_editor && !$user->canManageEditors))
                <div class="extra_info">
                    <p class="subHeading">Если отмечен один из пунктов, пользователь станет редактором</p>
                    <div class="inlineInput_line">
                        <input type="checkbox" name="canModifyMessages" @if(old('canModifyMessages', $userChange->canModifyMessages))checked{{""}}@endif /><label class="inline_label" for="canModifyMessages">Может изменять сообщения</label>
                    </div>
                    <div class="inlineInput_line">
                        <input type="checkbox" name="canDeleteMessages" @if(old('canDeleteMessages', $userChange->canDeleteMessages))checked{{""}}@endif /><label class="inline_label" for="canDeleteMessages">Может удалять сообщения</label>
                    </div>
                    <div class="inlineInput_line">
                        <input type="checkbox" name="canModifyUsers" @if(old('canModifyUsers', $userChange->canModifyUsers))checked{{""}}@endif /><label class="inline_label" for="canModifyUsers">Может изменять пользователей</label>
                    </div>
                    <div class="inlineInput_line">
                        <input type="checkbox" name="canDeleteUsers" @if(old('canDeleteUsers', $userChange->canDeleteUsers))checked{{""}}@endif /><label class="inline_label" for="canDeleteUsers">Может удалять пользователей</label>
                    </div>
                    <div class="inlineInput_line">
                        <input type="checkbox" name="canBlockUsers" @if(old('canBlockUsers', $userChange->canBlockUsers))checked{{""}}@endif /><label class="inline_label" for="canBlockUsers">Может блокировать пользователей</label>
                    </div>
                    <div class="inlineInput_line">
                        <input type="checkbox" name="canFilterUsers" @if(old('canFilterUsers', $userChange->canFilterUsers))checked{{""}}@endif /><label class="inline_label" for="canFilterUsers">Может включать фильтр для пользователей</label>
                    </div>
                    <div class="inlineInput_line">
                        <input type="checkbox" name="canHandleEditors" @if(old('canManageEditors', $userChange->canManageEditors))checked{{""}}@endif /><label class="inline_label" for="canManageEditors">Может добавлять и удалять редакторов</label>
                    </div>
                    <div class="inlineInput_line">
                        <input type="checkbox" name="canCleanChat" @if(old('canCleanChat', $userChange->canCleanChat))checked{{""}}@endif /><label class="inline_label" for="canCleanChat">Может очищать чат</label>
                    </div>
                </div>
                @endif
            </div>
            <input type="submit" class="button right editUser" value="Сохранить">
        </form>
    </div>

@endsection

@include('layouts.app')
