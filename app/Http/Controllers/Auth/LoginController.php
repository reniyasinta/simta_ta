<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.auth-login'); // View login kamu
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Gunakan accessor role_name dari model User
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
    $role = auth()->user()->role_name;

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
    \Log::info('User sebelum logout:', ['id' => auth()->id()]); // Log ID untuk debug

    Auth::guard('web')->logout(); // pastikan pakai guard 'web'
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
}

}
