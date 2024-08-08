@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('vehiculos.horario', $vehiculo) }}
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h1>Actualizar Días Activos de Rutas para el Vehículo</h1>
        <div class="row">
            <div class="col-lg-4">
                <p><strong>Placa:</strong> {{ $vehiculo->placa }}</p>
            </div>
            <div class="col-lg-4">
                <p><strong>Marca:</strong> {{ $vehiculo->marca }}</p>
            </div>
            <div class="col-lg-4">
                <p><strong>Modelo:</strong> {{ $vehiculo->modelo }}</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        
        <form action="{{ route('vehiculos.horario.actualizar',$vehiculo->id) }}" method="POST">
            @csrf
            @method('put')
            @foreach ($vehiculo->rutas as $ruta)
                <div class="card">
                    <div class="card-body">
                        <h3>{{ $ruta->nombre }}</h3>
                        <div class="form-group">
                            <label>Días Activos:</label><br>
                            @php
                                // Asegúrate de que siempre sea un array
                                $diasActivos = old("dias_activos.{$ruta->id}", $ruta->pivot->dias_activos ?? []);
                                if (!is_array($diasActivos)) {
                                    $diasActivos = json_decode($diasActivos, true) ?? [];
                                }
                            @endphp
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="dias_activos[{{ $ruta->id }}][]" value="lunes" id="lunes-{{ $ruta->id }}" {{ in_array('lunes', $diasActivos) ? 'checked' : '' }}>
                                <label class="form-check-label" for="lunes-{{ $ruta->id }}">Lunes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="dias_activos[{{ $ruta->id }}][]" value="martes" id="martes-{{ $ruta->id }}" {{ in_array('martes', $diasActivos) ? 'checked' : '' }}>
                                <label class="form-check-label" for="martes-{{ $ruta->id }}">Martes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="dias_activos[{{ $ruta->id }}][]" value="miercoles" id="miercoles-{{ $ruta->id }}" {{ in_array('miercoles', $diasActivos) ? 'checked' : '' }}>
                                <label class="form-check-label" for="miercoles-{{ $ruta->id }}">Miércoles</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="dias_activos[{{ $ruta->id }}][]" value="jueves" id="jueves-{{ $ruta->id }}" {{ in_array('jueves', $diasActivos) ? 'checked' : '' }}>
                                <label class="form-check-label" for="jueves-{{ $ruta->id }}">Jueves</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="dias_activos[{{ $ruta->id }}][]" value="viernes" id="viernes-{{ $ruta->id }}" {{ in_array('viernes', $diasActivos) ? 'checked' : '' }}>
                                <label class="form-check-label" for="viernes-{{ $ruta->id }}">Viernes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="dias_activos[{{ $ruta->id }}][]" value="sabado" id="sabado-{{ $ruta->id }}" {{ in_array('sabado', $diasActivos) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sabado-{{ $ruta->id }}">Sábado</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" name="dias_activos[{{ $ruta->id }}][]" value="domingo" id="domingo-{{ $ruta->id }}" {{ in_array('domingo', $diasActivos) ? 'checked' : '' }}>
                                <label class="form-check-label" for="domingo-{{ $ruta->id }}">Domingo</label>
                            </div>
                        </div>
                        @error("dias_activos.{$ruta->id}")
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endforeach
    
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('vehiculos.index') }}" class="btn btn-danger">Cancelar</a>
        </form>
    </div>
</div>

@endsection
