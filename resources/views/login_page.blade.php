@extends('layouts.main')

@section('content1')
    <div class="d-flex gap-4 align-items-center">
        <a href="/login_page" class="text-white text-decoration-none fw-bold fs-4 border-bottom border-2 border-light pb-1">Login</a>
        <a href="/register_page" class="text-secondary text-decoration-none fw-bold fs-4">Register</a>
        <a href="/" class="btn btn-outline-light text-white px-4 py-2 fs-4 border-2">Main</a>
    </div>
@endsection

@section('content2')
    <div class="row justify-content-center pt-5">
        <div class="col-lg-6 col-md-8">
            <div class="p-5 rounded-5" style="background-color: rgba(0,0,0,0.85); border: 1px solid #222; box-shadow: 0 0 50px rgba(0,0,0,0.8);">

                <h2 class="text-secondary opacity-50 mb-4 fw-light text-uppercase text-center" style="letter-spacing: 5px;">Sign In</h2>

                <form action="{{ route('login.index') }}" method="POST">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="text-secondary mb-2 opacity-75">Email Address</label>
                        <input type="email" name="email" class="form-control bg-dark text-white border-secondary py-2 @error('email') is-invalid @enderror" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label class="text-secondary mb-2 opacity-75">Password</label>
                        <input type="password" name="password" class="form-control bg-dark text-white border-secondary py-2 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="form-check mb-4">
                        <input class="form-check-input bg-dark border-secondary" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-secondary opacity-75" for="remember">
                            Remember me
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <div class="d-grid">
                        <button class="btn px-5 fw-bold text-light shadow-sm py-2" style="background-color: #2c3e50; border: 1px solid #1a252f;" type="submit">
                            Enter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
