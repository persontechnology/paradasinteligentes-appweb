@extends('layouts.guest')
@section('content')
<div class="card login-form animated pulse">
    <div class="card-header">Ingresar</div>
    <div class="card-body">
        <form method="POST" action="{{ route('login') }}" id="formValidate">
            @csrf

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Email Address -->
            

            <div class="form-floating form-control-feedback form-control-feedback-start mb-2">
                <div class="form-control-feedback-icon">
                    <i class="ph ph-envelope-simple"></i>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Placeholder" autofocus required>
                <label>Email</label>
                @error('email')
                <div class="invalid-feedback bold"><strong>{{ $message }}</strong></div>
                @enderror
            </div>



            <!-- Password -->
            <div class="form-floating form-control-feedback form-control-feedback-start mb-2">
                <div class="form-control-feedback-icon">
                    <i class="ph ph-lock"></i>
                </div>
                <input type="password" name="password"  class="form-control @error('password') is-invalid @enderror" placeholder="Placeholder" required>
                <label>Contraseña</label>
                @error('password')
                <div class="invalid-feedback bold"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            

            <!-- Remember Me -->
            <div class="form-check-horizontal mb-2">
                <label for="remember_me" class="form-check mb-0">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <span class="form-check-label">Acuérdate de mí</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-2">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <x-primary-button class="mt-2">
                Ingresar
            </x-primary-button>
        </form>
    </div>
    
</div>

@endsection
