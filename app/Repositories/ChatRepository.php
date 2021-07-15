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
            ->select(DB::raw('count(boch_likes.id) as likes_qty, SUM(boch_likes.user_id = ' . Auth::id() . ') as my_like, boch_chat.message, boch_users.color, boch_users.nickname, boch_chat.date, boch_chat.id'))
            ->groupBy(['chat.id'])
            ->orderBy('chat.id', 'desc')
            ->get();
        return $msgs;
    }

    public function isAuthorOfMessage(int $id) {
        $message = DB::table('chat')->select('user_id')->where('id', $id)->get()->first();
        return $message->user_id == Auth::id();
    }

}
