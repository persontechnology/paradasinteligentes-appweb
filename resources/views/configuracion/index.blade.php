
@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('configuracion.index') }}
@endsection

@section('content')

<form method="POST" action="{{ route('configuracion.store') }}">
    @csrf

    <div class="card">
        <div class="card-header">
            Configurar Frecuencia de Actualización
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-link-simple"></i>
                        </div>
                        <input type="text" name="url_web_gps" value="{{ old('url_web_gps',$configuracion->url_web_gps ) }}" class="form-control @error('url_web_gps') is-invalid @enderror" placeholder="Placeholder" required autofocus>
                        <label>URL Web GPS<i class="text-danger">*</i></label>
                        @error('url_web_gps')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-12 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-barcode"></i>
                        </div>
                        <input type="text" name="token" value="{{ old('token',$configuracion->token ) }}" class="form-control @error('token') is-invalid @enderror" placeholder="Placeholder" required autofocus>
                        <label>Token<i class="text-danger">*</i></label>
                        @error('token')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12 mb-2">
                    <label for="">Frecuencia</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ph ph-timer"></i>
                        </span>

                        <select name="frecuencia" class="form-select select @error('frecuencia') is-invalid @enderror"  >
                            <option value="every5Seconds" {{ $configuracion->frecuencia === 'every5Seconds' ? 'selected' : '' }}>Cada 5 segundos</option>
                            <option value="every10Seconds" {{ $configuracion->frecuencia === 'every10Seconds' ? 'selected' : '' }}>Cada 10 segundos</option>
                            <option value="every15Seconds" {{ $configuracion->frecuencia === 'every15Seconds' ? 'selected' : '' }}>Cada 15 segundos</option>
                            <option value="every20Seconds" {{ $configuracion->frecuencia === 'every20Seconds' ? 'selected' : '' }}>Cada 20 segundos</option>
                            <option value="every25Seconds" {{ $configuracion->frecuencia === 'every25Seconds' ? 'selected' : '' }}>Cada 25 segundos</option>
                            <option value="every30Seconds" {{ $configuracion->frecuencia === 'every30Seconds' ? 'selected' : '' }}>Cada 30 segundos</option>
                            <option value="every35Seconds" {{ $configuracion->frecuencia === 'every35Seconds' ? 'selected' : '' }}>Cada 35 segundos</option>
                            <option value="every40Seconds" {{ $configuracion->frecuencia === 'every40Seconds' ? 'selected' : '' }}>Cada 40 segundos</option>
                            <option value="every45Seconds" {{ $configuracion->frecuencia === 'every45Seconds' ? 'selected' : '' }}>Cada 45 segundos</option>
                            <option value="every50Seconds" {{ $configuracion->frecuencia === 'every50Seconds' ? 'selected' : '' }}>Cada 50 segundos</option>
                            <option value="every55Seconds" {{ $configuracion->frecuencia === 'every55Seconds' ? 'selected' : '' }}>Cada 55 segundos</option>

                            <option value="everyMinute" {{ $configuracion->frecuencia === 'everyMinute' ? 'selected' : '' }}>Cada minuto</option>
                            <option value="everyFiveMinutes" {{ $configuracion->frecuencia === 'everyFiveMinutes' ? 'selected' : '' }}>Cada 5 minutos</option>
                            <option value="everyTenMinutes" {{ $configuracion->frecuencia === 'everyTenMinutes' ? 'selected' : '' }}>Cada 10 minutos</option>
                            <option value="everyFifteenMinutes" {{ $configuracion->frecuencia === 'everyFifteenMinutes' ? 'selected' : '' }}>Cada 15 minutos</option>
                            <option value="everyThirtyMinutes" {{ $configuracion->frecuencia === 'everyThirtyMinutes' ? 'selected' : '' }}>Cada 30 minutos</option>
                            <option value="hourly" {{ $configuracion->frecuencia === 'hourly' ? 'selected' : '' }}>Cada hora</option>
                            <option value="everyTwoHours" {{ $configuracion->frecuencia === 'everyTwoHours' ? 'selected' : '' }}>Cada dos horas</option>
                            <option value="everyThreeHours" {{ $configuracion->frecuencia === 'everyThreeHours' ? 'selected' : '' }}>Tres veces al día</option>
                            <option value="everyFourHours" {{ $configuracion->frecuencia === 'everyFourHours' ? 'selected' : '' }}>Cuatro veces al día</option>
                            <option value="daily" {{ $configuracion->frecuencia === 'daily' ? 'selected' : '' }}>Diario</option>
                            <option value="weekly" {{ $configuracion->frecuencia === 'weekly' ? 'selected' : '' }}>Semanal</option>
                            <option value="monthly" {{ $configuracion->frecuencia === 'monthly' ? 'selected' : '' }}>Mensual</option>
                            <option value="yearly" {{ $configuracion->frecuencia === 'yearly' ? 'selected' : '' }}>Anual</option>
                        </select>
                                                
                    </div>
                    @error('frecuencia')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror

                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('dashboard') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </div>


</form>

@endsection

@push('scriptsHeader')
<script src="{{ asset('assets/js/vendor/forms/selects/bootstrap_multiselect.js') }}"></script>
@endpush
@push('scriptsFooter')

<script>
 $('.select').multiselect();
</script>
@endpush