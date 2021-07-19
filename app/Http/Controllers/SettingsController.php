<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\SettingsRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    private $settingsRepository;
    private $userRepository;

    public function __construct()
    {
        $this->settingsRepository = app(SettingsRepository::class);
        $this->userRepository = app(UserRepository::class);
    }

    public function usersSettings()
    {
        $user = Auth::user();
        $users = $this->settingsRepository->getUsers();
        return view('settings.users', compact('user','users'));
    }

    public function deleteUserAction(Request $request) {
        $user_id = $request->get('id');
        $user = User::all('id', 'is_admin', 'status')->find($user_id);
        if ($user->status == 'online') {
            return true;
        }
        elseif (!($user->is_admin || $user->id == Auth::id())) {
            User::find($user_id)->delete();
        }
    }

    public function blockUserAction(Request $request) {
        $user = User::find($request->get('id'));
        if ($request->get('status') == 'banned') {
            $user->status = 'offline';
            $user->save();
            return false;
        } else {
            $user->status = 'banned';
            $user->save();
            return true;
        }


    }


}
