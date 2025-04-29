@extends('layouts.auth')

@section('title', 'Login')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
    <style>
        body {
            background: url("{{ asset('img/poliban.jpeg') }}") no-repeat center center fixed;
            background-size: cover;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .judul-simta {
            color: black;
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: none;
            padding: 0;
            border-radius: 0;
            display: block;
            line-height: 1;
            width: 100%;
            white-space: nowrap;
            margin-left: -100px;
            margin-bottom: 40px;
        }

        .card.card-primary {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
        }
    </style>
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Sistem Informasi Tugas Akhir Simta</h4>
            <h4>Login</h4>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email"
                           type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email"
                           tabindex="1"
                           required
                           autofocus>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="d-block">
                        <label for="password" class="control-label">Password</label>
                        <div class="float-right">
                            <a href="{{ route('password.request') }}" class="text-small">
                                Lupa Password?
                            </a>
                        </div>
                    </div>
                    <input id="password"
                           type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password"
                           tabindex="2"
                           required>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit"
                            class="btn btn-primary btn-lg btn-block"
                            tabindex="4">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <!-- JS Libraries -->
@endpush
