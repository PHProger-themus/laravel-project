<?php

namespace App\Http\Controllers;

use App\Repositories\SettingsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    private $settingsRepository;

    public function __construct()
    {
        $this->settingsRepository = app(SettingsRepository::class);
    }

    public function usersSettings()
    {
        $user = Auth::user();
        $users = $this->settingsRepository->getUsers();
        return view('settings.users', compact('user','users'));
    }

}
