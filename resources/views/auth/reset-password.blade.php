@extends('layouts.guest')
@section('content')
    <div class="card">
        <div class="card-header">Resetear contraseña</div>
        <div class="card-body">
            <form method="POST" action="{{ route('password.store') }}" id="formValidate">
                @csrf
        
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
        
                <!-- Email Address -->
                <div class="form-floating form-control-feedback form-control-feedback-start mb-2">
                    <div class="form-control-feedback-icon">
                        <i class="ph ph-envelope-simple"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="" autofocus required>
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
                    <input type="password" name="password"  class="form-control @error('password') is-invalid @enderror" placeholder="" required>
                    <label>Nueva contraseña</label>
                    @error('password')
                    <div class="invalid-feedback bold"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>
        
                <!-- Confirm Password -->

                <div class="form-floating form-control-feedback form-control-feedback-start mb-2">
                    <div class="form-control-feedback-icon">
                        <i class="ph ph-lock"></i>
                    </div>
                    <input type="password" name="password_confirmation"  class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="" required>
                    <label>Confirme contraseña</label>
                    @error('password_confirmation')
                    <div class="invalid-feedback bold"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

        
                
        
                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        {{ __('Reset Password') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
        
    </div>
    
@endsection