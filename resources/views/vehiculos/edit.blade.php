@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('vehiculos.edit', $vehiculo) }}
@endsection

@section('content')
<form action="{{ route('vehiculos.update', $vehiculo->id) }}" method="POST" id="formValidate">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-file-vue"></i>
                        </div>
                        <input type="text" name="placa" value="{{ old('placa', $vehiculo->placa) }}" class="form-control @error('placa') is-invalid @enderror" placeholder="Placeholder" required autofocus>
                        <label>Placa<i class="text-danger">*</i></label>
                        @error('placa')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-barcode"></i>
                        </div>
                        <input type="text" name="codigo" value="{{ old('codigo', $vehiculo->codigo) }}" class="form-control @error('codigo') is-invalid @enderror" placeholder="Placeholder" required>
                        <label>Código<i class="text-danger">*</i></label>
                        @error('codigo')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-exam"></i>
                        </div>
                        <input type="text" name="marca" value="{{ old('marca', $vehiculo->marca) }}" class="form-control @error('marca') is-invalid @enderror" placeholder="Placeholder" required>
                        <label>Marca<i class="text-danger">*</i></label>
                        @error('marca')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-calendar"></i>
                        </div>
                        <input type="text" name="modelo" value="{{ old('modelo', $vehiculo->modelo) }}" class="form-control @error('modelo') is-invalid @enderror" placeholder="Placeholder" required>
                        <label>Modelo<i class="text-danger">*</i></label>
                        @error('modelo')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-number-square-one"></i>
                        </div>
                        <input type="number" name="numero_linea" value="{{ old('numero_linea',$vehiculo->numero_linea) }}" class="form-control @error('numero_linea') is-invalid @enderror" placeholder="Placeholder" required>
                        <label>Número de Línea<i class="text-danger">*</i></label>
                        @error('numero_linea')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-text-aa"></i>
                        </div>
                        <input type="text" name="nombre_cooperativa" value="{{ old('nombre_cooperativa', $vehiculo->nombre_cooperativa) }}" class="form-control @error('nombre_cooperativa') is-invalid @enderror" placeholder="Placeholder" required>
                        <label>Nombre de cooperativa<i class="text-danger">*</i></label>
                        @error('nombre_cooperativa')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start ">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-user"></i>
                        </div>
                        <select class="form-select @error('conductor') is-invalid @enderror" name="conductor" required>
                            @foreach ($usuarios as $conductor)
                                <option value="{{ $conductor->id }}" {{ old('conductor', $vehiculo->coductor_id) == $conductor->id ? 'selected' : '' }}>{{ $conductor->name }}</option>
                            @endforeach
                        </select>
                        <label>Conductor<i class="text-danger">*</i></label>
                        @error('conductor')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start ">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-person"></i>
                        </div>
                        <select class="form-select @error('ayudante') is-invalid @enderror" name="ayudante">
                            @foreach ($usuarios as $ayudante)
                                <option value="{{ $ayudante->id }}" {{ old('ayudante', $vehiculo->ayudante_id) == $ayudante->id ? 'selected' : '' }}>{{ $ayudante->name }}</option>
                            @endforeach
                        </select>
                        <label>Ayudante</label>
                        @error('ayudante')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12 mb-2">
                    <label for="">Rutas</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ph ph-chart-line"></i>
                        </span>

                        <select name="rutas[]" class="form-select multiselect @error('rutas') is-invalid @enderror" multiple="multiple" data-include-select-all-option="true" data-enable-filtering="true" data-enable-case-insensitive-filtering="true">
                            @foreach ($rutas as $ruta)
                                <option value="{{ $ruta->id }}" {{ $ruta->estado=='INACTIVO'?'disabled':'' }} {{ in_array($ruta->id, old('rutas', $vehiculo->rutas->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $ruta->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('rutas')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="col-lg-12 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start ">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-article"></i>
                        </div>
                        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" placeholder="Placeholder" style="height: 100px;">{{ old('descripcion', $vehiculo->descripcion) }}</textarea>
                        <label>Descripción</label>
                        @error('descripcion')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start ">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-toggle-left"></i>
                        </div>
                        <select class="form-select @error('estado') is-invalid @enderror" name="estado">
                            <option value="ACTIVO" {{ old('estado', $vehiculo->estado) == 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="INACTIVO" {{ old('estado', $vehiculo->estado) == 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                        <label>Estado</label>
                        @error('estado')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>
                
                
                
                <div class="col-lg-6 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-map-pin"></i>
                        </div>
                        
                        <input type="text" name="latitud" value="{{ old('latitud',$vehiculo->ubicacion_actual[0]) }}" class="form-control @error('latitud') is-invalid @enderror" placeholder="Placeholder" required>
                        <label>Latitud<i class="text-danger">*</i></label>
                        @error('latitud')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6 mb-2">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-map-pin"></i>
                        </div>
                        <input type="text" name="longitud" value="{{ old('longitud',$vehiculo->ubicacion_actual[1]) }}" class="form-control @error('longitud') is-invalid @enderror" placeholder="Placeholder" required>
                        <label>Longitud<i class="text-danger">*</i></label>
                        @error('longitud')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                Guardar
            </button>
            <a href="{{ route('vehiculos.index') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </div>
</form>
@endsection

@push('scriptsHeader')
<script src="{{ asset('assets/js/vendor/forms/selects/bootstrap_multiselect.js') }}"></script>
@endpush

@push('scriptsFooter')
<script>
    $('.multiselect').multiselect({
        nonSelectedText: 'Seleccione rutas',
        nSelectedText: 'seleccionadas',
        allSelectedText: 'Todas seleccionadas',
        numberDisplayed: 1,
        selectAllText: 'Seleccionar todo',
        filterPlaceholder: 'Buscar'
    });
</script>
@endpush
