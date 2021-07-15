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
        $msgs = \DB::table('chat')->join('users', 'users.id', '=', 'chat.user_id')->select(['chat.message', 'users.color', 'users.nickname', 'chat.date', 'chat.id'])->orderBy('chat.id', 'DESC')->get();
        return $msgs;
    }

    public function isAuthorOfMessage(int $id) {
        $message = DB::table('chat')->select('user_id')->where('id', $id)->get()->first();
        return $message->user_id == Auth::id();
    }

}
