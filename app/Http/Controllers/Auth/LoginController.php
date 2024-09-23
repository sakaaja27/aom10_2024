<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Hash;

class LoginController extends Controller
{
    public function loginpage()
    {
        return view('auth.login');
    }
    public function registerpage()
    {
        return view('auth.register');
    }
    public function loginmethod(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended(RouteServiceProvider::HOME);
        } else {
            return redirect('/login')->withErrors(['error' => 'Akun atau Password salah']);
        }
    }

    public function registermethod(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8',
        ]);

        if ($request->password != $request->confirm_password) {
            return back()->withErrors(['password' => 'Pastikan password dan confirm password sama']);
        }
        $user = User::create([
            'name' => $request->username,
            'telp' => $request->no_telp,
            'email' => $request->email,
            'role' => 'USR-P',
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
    public function logoutmethod(Request $request){
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
