<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $login = trim($request->input('login'));
        $password = $request->input('password');

        $user = User::where('email', $login)
            ->orWhereRaw('LOWER(name) = ?', [strtolower($login)])
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            $this->activityLogService->log('Login', "{$user->name} masuk ke sistem.");

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'login' => 'Kredensial yang Anda masukkan tidak sesuai. Silakan coba lagi.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        $this->activityLogService->log('Logout', "{$request->user()->name} keluar dari sistem.");

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
