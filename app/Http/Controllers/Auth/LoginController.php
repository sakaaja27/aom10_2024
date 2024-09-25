<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'email' => 'required|email|exists:users',
            'password' => 'required|min:8',
        ]);
        $credentials = $request->only('email', 'password');
        // Check if the email is verified
        $user = User::where('email', $credentials['email'])->first();
        // Attempt to authenticate with password hashing
        if (Hash::check($request->password, $user->password)) {
            Auth::login($user);
            // Check user role
            if (Auth::user()->role === 'ADMIN') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->back()->withErrors(['password' => 'Invalid credentials.']);
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

        $user = User::create([
            'name' => $request->username,
            'telp' => $request->no_telp,
            'email' => $request->email,
            'role' => 'USR-Px',
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
