<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    private $userRepository;

    public function __construct() {
        $this->userRepository = app(UserRepository::class);
    }

    public function index(Request $request) {
        $name = $this->userRepository->getUserNickname();
        return view('chat', compact('name'));
    }

}
