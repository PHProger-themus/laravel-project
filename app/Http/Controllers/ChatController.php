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
        $messages = $this->chatRepository->getMessages();
        return view('chat', compact('user', 'messages'));
    }

    public function sendMessage(Request $request) {
        $date = date('d.m.y в H:i');
        DB::table('chat')->insert(['user_id' => Auth::id(), 'message' => $request->get('message'), 'date' => $date]);
    }

    public function editMessage(Request $request) {
        $id = $request->get('id');
        if ($this->chatRepository->isAuthorOfMessage($id)) {
            DB::table('chat')->where('id', $id)->update(['message' => $request->get('text')]);
            return true;
        }
        return false;
    }

    public function deleteMessage(Request $request) {
        $id = $request->get('id');
        if ($this->chatRepository->isAuthorOfMessage($id)) {
            DB::table('chat')->delete(['id' => $id]);
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
}
