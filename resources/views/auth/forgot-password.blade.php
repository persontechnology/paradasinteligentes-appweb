@extends('layouts.guest')
@section('content')
<div class="card login-form animated pulse">
    <div class="card-header">¿Olvidaste tu contraseña?</div>
    <div class="card-body">
        <div class="mb-4 text-sm text-gray-600">
            No hay problema. Solo indícanos tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña que te permitirá elegir una nueva.
        </div>
        
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        
        <form method="POST" action="{{ route('password.email') }}" id="formValidate">
            @csrf
        
            <!-- Email Address -->
            <div class="form-floating form-control-feedback form-control-feedback-start mb-2">
                <div class="form-control-feedback-icon">
                    <i class="ph ph-envelope-simple"></i>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Placeholder" autofocus required>
                <label>Email</label>
                @error('email')
                <div class="invalid-feedback bold">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    Enlace de restablecimiento de contraseña de correo electrónico
                </x-primary-button>
                
            </div>
        </form>
    </div>
    
</div>

@endsection