<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ChatAuthController extends Controller
{

    private $userRepository;

    public function __construct() {
        $this->userRepository = app(UserRepository::class);
    }

    public function reg(Request $request)
    {

        $validationRules = [
            'nickname' => 'min:3|max:20|required|unique:users,nickname',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ];
        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            $request->session()->flash('form', 'reg');
            return back()->withErrors($validator, 'register')->withInput();
        }

        $data = $request->input();
        $user = User::create($data);

        if ($user) {
            Auth::login($user);
            return redirect()->route('chat.chat')->with(['success', 'Здравствуйте, ' . $data['nickname'] . '! Добро пожаловать!']);
        } else {
            return back()->withErrors(['error' => 'Неизвестная ошибка сохранения'], 'register')->withInput();
        }

    }

    public function logout(Request $request) {
        return $this->userRepository->makeOffline($request);
    }

    public function auth(Request $request) {

        $validationRules = [
            'nickname' => 'min:3|max:30|required|exists:users,nickname',
            'password' => 'required|min:8'
        ];
        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            return back()->withErrors($validator, 'auth')->withInput();
        }

        if (!$user = $this->userRepository->makeOnline($request->nickname, $request->password)) {
            return back()->withErrors(['error' => 'Неверный пароль для пользователя ' . $request->nickname], 'auth')->withInput();
        }
        Auth::login($user);
        return redirect()->route('chat.chat');

    }

}
