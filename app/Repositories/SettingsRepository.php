<?php


namespace App\Repositories;


use Illuminate\Support\Facades\DB;

class SettingsRepository extends CoreRepository
{

    public function getClass()
    {
        return false;
    }

    public function getUsers() {
        $users = DB::table('users')
            ->leftJoin('chat', 'users.id', '=', 'chat.user_id')
            ->select(DB::raw('boch_users.id, boch_users.nickname, boch_users.email, boch_users.color, boch_users.status, boch_users.is_editor, boch_users.is_admin, boch_users.last_online, boch_users.created_at, count(boch_chat.user_id) as msg_count'))
            ->groupBy('chat.user_id')
            ->get();

            return $users;
    }

}
