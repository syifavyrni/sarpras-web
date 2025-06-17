<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate and get validated credentials
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Attempt login with validated credentials
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        // If login fails, return back with error message
        return back()->withErrors([
            'username' => 'Login gagal. Periksa kembali username dan password Anda.'
        ]);
    }
}
