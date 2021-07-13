<?php

namespace App\Repositories;

use App\Models\User as Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository extends CoreRepository
{

    public function getClass() {
        return Model::class;
    }

    public function makeOffline() {
        $this->startQuery()->where('id', Auth::id())->update(['status' => 'offline']);
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
