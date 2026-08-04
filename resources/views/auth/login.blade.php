@extends('layouts.user')

@section('title', 'Login Admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')

<section
    class="login-page"
    style="background-image: url('{{ asset('images/hero.jpg') }}');"
>
    <div class="login-overlay"></div>

    <div class="login-container">

        <div class="login-card">

            <div class="login-header">

                <img
                    src="{{ asset('images/logo-cileunyi.jpg') }}"
                    alt="Logo SIG SPPG"
                    class="login-logo"
                >

                <h1>SIG SPPG</h1>

                <p>
                    Kecamatan Cileunyi
                </p>

            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form
                action="{{ route('login.process') }}"
                method="POST"
            >
                @csrf

                <div class="mb-3">

                    <label
                        for="email"
                        class="form-label login-label"
                    >
                        Email
                    </label>

                    <div class="login-input-wrapper">

                        <i class="bi bi-envelope"></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control login-input @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="Masukkan email"
                            autocomplete="email"
                            autofocus
                            required
                        >

                    </div>

                    @error('email')
                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label
                        for="password"
                        class="form-label login-label"
                    >
                        Password
                    </label>

                    <div class="login-input-wrapper">

                        <i class="bi bi-lock"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control login-input @error('password') is-invalid @enderror"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            id="togglePassword"
                            aria-label="Tampilkan password"
                        >
                            <i class="bi bi-eye"></i>
                        </button>

                    </div>

                    @error('password')
                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-check mb-4">

                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="form-check-input"
                        value="1"
                    >

                    <label
                        for="remember"
                        class="form-check-label"
                    >
                        Ingat saya
                    </label>

                </div>

                <button
                    type="submit"
                    class="login-submit"
                >
                    <i class="bi bi-box-arrow-in-right"></i>
                    Login
                </button>

            </form>

            <a
                href="{{ route('user.dashboard') }}"
                class="login-back"
            >
                <i class="bi bi-arrow-left"></i>
                Kembali ke halaman utama
            </a>

        </div>

    </div>

</section>

@endsection

@section('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleButton =
        document.getElementById('togglePassword');

    const passwordInput =
        document.getElementById('password');

    if (!toggleButton || !passwordInput) {
        return;
    }

    toggleButton.addEventListener('click', function () {
        const isPassword =
            passwordInput.type === 'password';

        passwordInput.type =
            isPassword ? 'text' : 'password';

        this.innerHTML = isPassword
            ? '<i class="bi bi-eye-slash"></i>'
            : '<i class="bi bi-eye"></i>';
    });
});
</script>

@endsection