<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withInput()->withErrors(['login' => 'Username atau password salah.']);
        }

        if (!$user->is_active) {
            return back()->withInput()->withErrors(['login' => 'Akun Anda telah dinonaktifkan oleh Administrator. Silakan hubungi admin untuk mengaktifkan kembali.']);
        }

        // Check password - support both hashed and plain_password for migration period
        $passwordValid = false;

        if (Hash::check($request->password, $user->password)) {
            $passwordValid = true;
        } elseif ($user->plain_password && $request->password === $user->plain_password) {
            // Legacy plain password match - hash it now for future logins
            $user->password = Hash::make($request->password);
            $user->save();
            $passwordValid = true;
        }

        if (!$passwordValid) {
            return back()->withInput()->withErrors(['login' => 'Username atau password salah.']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
