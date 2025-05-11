<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // ✅ tambahkan ini!

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.auth-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $role = $user->role_name;

            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'panitia':
                    return redirect()->route('panitia.dashboard');
                case 'dosen':
                    return redirect()->route('dosen.dashboard');
                case 'mahasiswa':
                    return redirect()->route('mahasiswa.dashboard');
                default:
                    return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function redirectTo()
    {
        $role = Auth::user()->role_name;

        return match ($role) {
            'admin' => '/admin/dashboard',
            'dosen' => '/dosen/dashboard',
            'panitia' => '/panitia/dashboard',
            'mahasiswa' => '/mahasiswa/dashboard',
            default => '/',
        };
    }

    public function logout(Request $request)
    {
        // ✅ Gunakan Auth::user()->id untuk dapatkan ID user
        Log::info('User sebelum logout:', ['id' => Auth::user()->id]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
