<?php

namespace App\Http\Controllers;

use App\Repositories\ChatRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{

    private $userRepository;
    private $chatRepository;

    public function __construct() {
        $this->userRepository = app(UserRepository::class);
        $this->chatRepository = app(ChatRepository::class);
    }

    public function index(Request $request) {
        $user = $this->userRepository->getUser();
        $chat_data['pinned'] = $this->chatRepository->getPinnedMessage();
        $chat_data['messages'] = $this->chatRepository->getMessages();
        return view('chat', compact('user', 'chat_data'));
    }

    public function sendMessage(Request $request) {
        $date = date('d.m.y в H:i');
        $mes_id = DB::table('chat')->insertGetId(['user_id' => Auth::id(), 'message' => $request->get('message'), 'date' => $date]);
        return $mes_id;
    }

    public function editMessage(Request $request) {
        $id = $request->get('id');
        $user = $this->userRepository->getUser();
        if ($this->chatRepository->isAuthorOfMessage($id, $user) || ($user->is_editor && $user->canModifyMessages)) {
            DB::table('chat')->where('id', $id)->update(['message' => $request->get('text')]);
            return true;
        }
        return false;
    }

    public function deleteMessage(Request $request) {
        $id = $request->get('id');
        $user = $this->userRepository->getUser();
        if ($this->chatRepository->isAuthorOfMessage($id, $user) || ($user->is_editor && $user->canDeleteMessages)) {
            DB::table('chat')->delete(['id' => $id]);
            DB::table('likes')->where(['message_id' => $id])->delete();
            return true;
        }
        return false;
    }

    public function likeMessage(Request $request) {
        $id = $request->get('id');
        $hasLike = DB::table('likes')->select('id')->where(['message_id' => $id, 'user_id' => Auth::id()])->get()->first();
        if (!$hasLike) {
            DB::table('likes')->insert(['message_id' => $id, 'user_id' => Auth::id()]);
            return true;
        } else {
            DB::table('likes')->delete($hasLike->id);
            return false;
        }
    }

    public function pinMessage(Request $request) {
        $id = $request->get('id');
        if ($request->get('pin')) {
            DB::table('chat')->where('is_pinned', 1)->update(['is_pinned' => 0]);
            DB::table('chat')->where('id', $id)->update(['is_pinned' => 1]);
            $message = DB::table('chat')
                ->join('users', 'users.id', '=', 'chat.user_id')
                ->select(['chat.message', 'chat.date', 'users.nickname'])
                ->where('chat.id', $id)
                ->get()->first();
            return [
                'nickname' => $message->nickname,
                'message' => $message->message,
                'date' => $message->date
            ];
        } else {
            DB::table('chat')->where('is_pinned', 1)->update(['is_pinned' => 0]);
        }
    }

}
