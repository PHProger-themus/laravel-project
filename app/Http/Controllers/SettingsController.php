<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\SettingsRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $user = $this->userRepository->getUser();
        if ($user->is_admin || ($user->is_editor && $user->canModifyUsers)) {
            $users = $this->settingsRepository->getUsers();
            return view('settings.users', compact('user','users'));
        } else {
            return redirect()->route('chat.chat');
        }
    }

    public function deleteUserAction(Request $request) {
        $me = $this->userRepository->getUser();
        if ($me->is_admin || ($me->is_editor && $me->canDeleteUsers)) {
            $user_id = $request->get('id');
            $user = User::all('id', 'is_admin', 'status')->find($user_id);
            if (!$user->is_admin && $user->id != Auth::id()) {
                if ($user->status == 'online') {
                    return true;
                } elseif (!($user->is_admin || $user->id == Auth::id())) {
                    User::find($user_id)->delete();
                }
            }
        }
    }

    public function blockUserAction(Request $request) {
        $me = $this->userRepository->getUser();
        if ($me->is_admin || ($me->is_editor && $me->canBlockUsers)) {
            $user = User::find($request->get('id'));
            if (!$user->is_admin && $user->id != Auth::id()) {
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
    }

    public function editUserAction(Request $request) {
        $user = $this->userRepository->getUser();
        if ($user->is_admin || ($user->is_editor && $user->canModifyUsers)) {
            $userChangeId = $request->get('id');
            $userChange = $this->userRepository->getUserForEditing($userChangeId);
            $rgbArray = explode(',', substr($userChange->color, 4, -1));
            $userChange->color = sprintf("#%02x%02x%02x", $rgbArray[0], $rgbArray[1], $rgbArray[2]);
            return view('settings.user', compact('user','userChange', 'userChangeId'));
        }
    }

    public function modifyUserAction(Request $request)
    {

        $user = $this->userRepository->getUser();
        if ($user->is_admin || ($user->is_editor && $user->canModifyUsers)) {

            $validationRules = [
                'nickname' => 'min:3|max:20|required',
                'email' => 'required|email',
            ];
            $validator = Validator::make($request->all(), $validationRules);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $oldName = $request->nickname;

            $this->userRepository->modifyUser($request, $user);

            return back()->with(['success' => 'Пользователь ' . $oldName . ' изменен']);

        }

    }

}
