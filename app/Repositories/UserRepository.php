<?php

namespace App\Repositories;

use App\Models\User as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository extends CoreRepository
{

    public function getClass() {
        return Model::class;
    }

    public function makeOffline(Request $request = null) {
        $user = $this->startQuery()->select(['status'])->where('id', Auth::id())->get()->first();
        if ($user->status != 'banned') {
            $this->startQuery()->where('id', Auth::id())->update(['status' => 'offline']);
        }
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('chat.index');
    }

    public function makeOnline(string $nickname, string $password) {
        $user = $this->startQuery()->where('nickname', $nickname)->get()->first();
        if (Hash::check($password, $user->password)) {
            if ($user->status == 'banned') {
                return 2;
            } else {
                $user->status = 'online';
                $user->save();
                return $user;
            }
        } else {
            return 1;
        }
    }

    public function getUser() {
        return $this->startQuery()->leftJoin('editors', 'users.id', '=', 'editors.user_id')->select()->where('users.id', Auth::id())->get()->first();
    }

    public function getUserForEditing(int $id) {
        return $this->startQuery()->leftJoin('editors', 'users.id', '=', 'editors.user_id')->select()->where('users.id', $id)->get()->first();
    }

    public function modifyUser(Request $request, $user) {

            $userToModifyId = $request->id;
            $userToModify = $this->getUserForEditing($userToModifyId);

            if(!$user->is_admin) {
                $request->is_admin = $userToModify->is_admin;
            } else {
                if ($request->is_admin == 'on') $request->is_admin = 1;
                else $request->is_admin = 0;
            }

            $hexColor = $request->color;
            $rgbColor = sscanf($hexColor, "#%02x%02x%02x");
            $color = 'rgb(' . $rgbColor[0] . ',' . $rgbColor[1] . ',' . $rgbColor[2] . ')';

            DB::table('users')->where('id', '=', $userToModifyId)->update([
                'nickname' => $request->nickname,
                'email' => $request->email,
                'is_admin' => $request->is_admin,
                'color' => $color
            ]);

            if (!($user->is_editor && !$user->canManageEditors)) {

                $privilegesArray = [
                    $request->canModifyMessages,
                    $request->canDeleteMessages,
                    $request->canModifyUsers,
                    $request->canDeleteUsers,
                    $request->canBlockUsers,
                    $request->canFilterUsers,
                    $request->canManageEditors,
                    $request->canCleanChat,
                ];

                $editorExists = DB::table('editors')->select()->where('user_id', '=', $userToModifyId)->get()->first();
                $werePrivilegesApplied = 0;
                for ($i = 0; $i < count($privilegesArray); $i++) {
                    if ($privilegesArray[$i] == 'on') {
                        if (!$werePrivilegesApplied) $werePrivilegesApplied = 1;
                        $privilegesArray[$i] = 1;
                    } else {
                        $privilegesArray[$i] = 0;
                    }
                }

                //dd($editorExists, $werePrivilegesApplied);

                $privilegesToDatabase = [
                    'user_id' => $userToModifyId, // Для INSERT
                    'canModifyMessages' => $privilegesArray[0],
                    'canDeleteMessages' => $privilegesArray[1],
                    'canModifyUsers' => $privilegesArray[2],
                    'canDeleteUsers' => $privilegesArray[3],
                    'canBlockUsers' => $privilegesArray[4],
                    'canFilterUsers' => $privilegesArray[5],
                    'canManageEditors' => $privilegesArray[6],
                    'canCleanChat' => $privilegesArray[7],
                ];

                if ($editorExists && $werePrivilegesApplied) {
                    DB::table('editors')->where('user_id', '=', $userToModifyId)->update($privilegesToDatabase);
                } elseif (!$editorExists && $werePrivilegesApplied) {
                    DB::table('editors')->insert($privilegesToDatabase);
                    DB::table('users')->where('id', '=', $userToModifyId)->update(['is_editor' => 1]);
                } elseif ($editorExists && !$werePrivilegesApplied) {
                    DB::table('editors')->where('user_id', '=', $userToModifyId)->delete();
                    DB::table('users')->where('id', '=', $userToModifyId)->update(['is_editor' => 0]);
                }

            }

        }

}
