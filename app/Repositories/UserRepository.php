<?php

namespace App\Repositories;

use App\Models\User as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository extends CoreRepository
{

    public function getClass() {
        return Model::class;
    }

    public function makeOffline(Request $request) {
        $this->startQuery()->where('id', Auth::id())->update(['status' => 'offline']);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('chat.index');
    }

    public function makeOnline(string $nickname, string $password) {
        $user = $this->startQuery()->where('nickname', $nickname)->get()->first();
        if (Hash::check($password, $user->password)) {
            $user->status = 'online';
            $user->save();
            return $user;
        } else {
            return false;
        }
    }

    public function getUser() {
        return $this->startQuery()->select()->where('id', Auth::id())->get()[0];
    }

}
