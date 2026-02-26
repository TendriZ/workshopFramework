@extends('layouts.auth')

@section('content')
<div class="row flex-grow">
    <div class="col-lg-6 mx-auto d-flex align-items-center">
        <div class="auth-form-light text-left p-5 w-100">
            <div class="brand-logo">
                <img src="{{ asset('template/images/logo.svg') }}">
            </div>
            <h4>Baru di sini?</h4>
            <h6 class="font-weight-light">Mendaftar itu mudah. Hanya butuh beberapa langkah.</h6>

            <form class="pt-3" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Name" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password-confirm" type="password" class="form-control form-control-lg" name="password_confirmation" placeholder="Konfirmasi Password" required autocomplete="new-password">
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">DAFTAR</button>
                </div>

                <div class="text-center mt-4 font-weight-light"> Sudah punya akun? <a href="{{ route('login') }}" class="text-primary">Masuk</a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-lg-6 register-half-bg d-none d-lg-flex flex-row" style="background: url('{{ asset('template/images/auth/register-bg.jpg') }}') no-repeat center center; background-size: cover;">
        <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; {{ date('Y') }}  All rights reserved.</p>
    </div>
</div>
@endsection