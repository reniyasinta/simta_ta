<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('pages.auth.auth-login'); // Pastikan Anda memiliki view ini
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $role = $user->getRoleNames()->first();


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
            if (auth()->user()->hasRole('admin')) {
                return '/admin/dashboard';
            } elseif (auth()->user()->hasRole('dosen')) {
                return '/dosen/dashboard';
            } elseif (auth()->user()->hasRole('mahasiswa')) {
                return '/mahasiswa/dashboard';
            } elseif (auth()->user()->hasRole('panitia')) {
                return '/panitia/dashboard';
            return '/';
        }
    }
}

