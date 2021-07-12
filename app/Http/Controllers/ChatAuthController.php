<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatAuthController extends Controller
{
    public function auth(UserCreateRequest $request)
    {
        $data = $request->input();
        $user = User::create($data);

        if ($user) {
            Auth::login($user);
            return redirect()->route('chat.chat')->with(['success', 'Здравствуйте, ' . $data['nickname'] . '! Добро пожаловать!']);
        } else {
            return back()->withErrors(['error' => 'Неизвестная ошибка сохранения'])->withInput();
        }

    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('chat.index');
    }

}
