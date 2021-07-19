<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuth
{

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->status != 'banned') {
            return $next($request);
        } else {
            if (Auth::check()) {
                app(UserRepository::class)->makeOffline();
            }
            return redirect()->route('chat.index');
        }
    }

}
