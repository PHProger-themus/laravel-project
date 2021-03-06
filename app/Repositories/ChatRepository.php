<?php


namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatRepository extends CoreRepository
{

    public function getClass()
    {
        return false;
    }

    public function getMessages() {
        $msgs = \DB::table('chat')
            ->join('users', 'users.id', '=', 'chat.user_id')
            ->leftJoin('likes', 'likes.message_id', '=', 'chat.id')
            //->select(['chat.message', 'users.color', 'users.nickname', 'chat.date', 'chat.id'])
            ->select(DB::raw('count(boch_likes.id) as likes_qty,
            SUM(boch_likes.user_id = ' . Auth::id() . ') as my_like,
            (boch_chat.user_id = ' . Auth::id() . ') as my_mes,
            (boch_chat.user_id = ' . Auth::id() . ' OR ' . Auth::user()->is_admin . ') as can_modify,
            boch_chat.message, boch_users.color, boch_users.id AS msg_owner, boch_users.nickname, boch_chat.date, boch_chat.id'))
            ->groupBy(['chat.id'])
            ->orderBy('chat.id', 'desc')
            ->get();
        return $msgs;
    }

    public function isAuthorOfMessage(int $id, $user) {
        $message = DB::table('chat')->select('user_id')->where('id', $id)->get()->first();
        return $message->user_id == Auth::id() || $user->is_admin;
    }

    public function getPinnedMessage() {
        $msgs = \DB::table('chat')
            ->join('users', 'users.id', '=', 'chat.user_id')
            ->select(['users.id AS msg_owner', 'users.nickname', 'chat.id', 'chat.message', 'chat.date'])
            ->where(['chat.is_pinned' => 1])
            ->get()->first();

        return $msgs;
    }

}
