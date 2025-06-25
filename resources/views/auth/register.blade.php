<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - SibansosTanser</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: linear-gradient(120deg, #2980b9, #8e44ad);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-box {
            width: 100%;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            padding: 2rem;
        }

        .register-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-logo img {
            width: 100px;
            height: auto;
            margin-bottom: 1rem;
        }

        .register-logo h2 {
            color: #2d3748;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .register-logo h2 span {
            font-weight: 400;
        }

        .card {
            background: none;
            border: none;
        }

        .card-header {
            background: none;
            border: none;
            text-align: center;
            padding: 0 0 1.5rem 0;
        }

        .card-header h4 {
            color: #2d3748;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
            background: none;
            border: none;
        }

        .btn-primary {
            width: 100%;
            padding: 0.8rem;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: #fed7d7;
            color: #c53030;
            border: 1px solid #feb2b2;
        }

        .text-center {
            text-align: center;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        a {
            color: #3b82f6;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        a:hover {
            color: #2563eb;
        }

        .invalid-feedback {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .is-invalid {
            border-color: #e53e3e;
        }

        .is-invalid:focus {
            border-color: #e53e3e;
            box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
        }

        @media (max-width: 640px) {
            .register-box {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-box">
        <div class="register-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:100px; height:100px; margin-bottom:1rem; border-radius:50%; background:none;">
            <h2><b>SibansosTanser</b></h2>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Register</h4>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                               name="username" value="{{ old('username') }}" placeholder="Username" required>
                        @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" placeholder="Email" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" placeholder="Password" required>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                               name="password_confirmation" placeholder="Konfirmasi Password" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Role is now hidden and defaults to 'penduduk' -->
                    <input type="hidden" name="role" value="penduduk">

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>

                <p class="mt-3 text-center">
                        <a href="{{ route('login') }}">
                            Sudah punya akun? Login di sini
                        </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>