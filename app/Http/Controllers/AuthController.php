<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(): View
    {
        return view('auth.login');
    }

    public function authenticate(LoginRequest $request): RedirectResponse 
    {
        if (!Auth::attempt($request->validated())) {
            return back()->withErrors([
                'status' => "Email atau password anda salah"
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        Auth::user()->update([
            'last_login' => now()
        ]);
        return redirect()->intended('dashboard');
    }
    
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'you have been logged out.');
    }
}
