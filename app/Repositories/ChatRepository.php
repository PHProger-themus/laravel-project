<?php


namespace App\Repositories;

class ChatRepository extends CoreRepository
{

    public function getClass()
    {
        return false;
    }

    public function getMessages() {
        $msgs = \DB::table('chat')->join('users', 'users.id', '=', 'chat.user_id')->select(['chat.message', 'users.color', 'users.nickname', 'chat.date'])->orderBy('chat.id', 'DESC')->get();
        return $msgs;
    }

}
