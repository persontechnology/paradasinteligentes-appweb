@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('rutas.create') }}
@endsection

@section('content')
<form action="{{ route('rutas.store') }}" method="post" id="formValidate">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Nombre de la Ruta -->
                <div class="col-lg-12 mb-2">
                    <div class="form-floating">
                        <input type="text" name="nombre_ruta" class="form-control @error('nombre_ruta') is-invalid @enderror" placeholder="Nombre de la ruta" value="{{ old('nombre_ruta') }}" required autofocus>
                        <label for="nombre_ruta">Nombre de la Ruta</label>
                        @error('nombre_ruta')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Ida de Ruta -->
                <div class="col-lg-4 mb-2">
                    <div class="border p-3">
                        <h6 class="text-center">IDA DE RUTA:</h6>
                        <div class="mb-2">
                            <label class="form-label">Inicia</label>
                            <input type="time" name="ida_inicio" class="form-control @error('ida_inicio') is-invalid @enderror" value="{{ old('ida_inicio', '06:20') }}" required>
                            @error('ida_inicio')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Finaliza</label>
                            <input type="time" name="ida_finaliza" class="form-control @error('ida_finaliza') is-invalid @enderror" value="{{ old('ida_finaliza', '20:20') }}" required>
                            @error('ida_finaliza')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Tiempo Total</label>
                            <input type="text" name="ida_tiempo_total" class="form-control @error('ida_tiempo_total') is-invalid @enderror" value="{{ old('ida_tiempo_total', '35 minutos') }}" required>
                            @error('ida_tiempo_total')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                    </div>
                </div>
            
                <!-- Retorno de Ruta -->
                <div class="col-lg-4 mb-2">
                    <div class="border p-3">
                        <h6 class="text-center">RETORNO DE RUTA:</h6>
                        <div class="mb-2">
                            <label class="form-label">Inicia</label>
                            <input type="time" name="retorno_inicio" class="form-control @error('retorno_inicio') is-invalid @enderror" value="{{ old('retorno_inicio', '06:20') }}" required>
                            @error('retorno_inicio')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Finaliza</label>
                            <input type="time" name="retorno_finaliza" class="form-control @error('retorno_finaliza') is-invalid @enderror" value="{{ old('retorno_finaliza', '20:20') }}" required>
                            @error('retorno_finaliza')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Tiempo Total</label>
                            <input type="text" name="retorno_tiempo_total" class="form-control @error('retorno_tiempo_total') is-invalid @enderror" value="{{ old('retorno_tiempo_total', '35 minutos') }}" required>
                            @error('retorno_tiempo_total')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                    </div>
                </div>
            
                <!-- Distancia Total -->
                <div class="col-lg-4 mb-2">
                    <div class="border p-3">
                        <h6 class="text-center">DISTANCIA TOTAL:</h6>
                        <div class="mb-2">
                            <label class="form-label">Distancia</label>
                            <input type="text" name="distancia_total" class="form-control @error('distancia_total') is-invalid @enderror" value="{{ old('distancia_total', '35 Km.') }}" required>
                            @error('distancia_total')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Tiempo Total de Ruta</label>
                            <input type="text" name="tiempo_total_ruta" class="form-control @error('tiempo_total_ruta') is-invalid @enderror" value="{{ old('tiempo_total_ruta', '70 minutos') }}" required>
                            @error('tiempo_total_ruta')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <div class="form-floating form-control-feedback form-control-feedback-start ">
                                <div class="form-control-feedback-icon">
                                    <i class="ph ph-toggle-left"></i>
                                </div>
                                <select class="form-select @error('estado') is-invalid @enderror" name="estado" required>
                                    <option value="ACTIVO" {{ old('estado')=='ACTIVO'?'selected':'' }}>ACTIVO</option>
                                    <option value="INACTIVO" {{ old('estado')=='INACTIVO'?'selected':'' }}>INACTIVO</option>
                                </select>
                                <label>Estado</label>
                                @error('estado')
                                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="col-lg-12 mb-2">
                    <label for="vehiculos">Seleccione vehículos</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ph ph-car-simple"></i>
                        </span>
                        <select name="vehiculos[]" class="form-select @error('vehiculos') is-invalid @enderror" id="vehiculos" multiple="multiple" data-include-select-all-option="true" data-enable-filtering="true" data-enable-case-insensitive-filtering="true" required>
                            @foreach ($vehiculos as $ve)
                                <option value="{{ $ve->id }}" {{ (collect(old('vehiculos'))->contains($ve->id)) ? 'selected' : '' }}>
                                    {{ $ve->numero_linea }} {{ $ve->numero_linea ? '-' : '' }} {{ $ve->codigo }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehiculos')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12 mb-2">
                    <div class="border p-2">
                        <strong>Detalle de Ida:</strong>
                        <textarea name="detalle_recorrido_ida" class="form-control @error('detalle_recorrido_ida') is-invalid @enderror" rows="4" required>{{ old('detalle_recorrido_ida') }}</textarea>
                        @error('detalle_recorrido_ida')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                        <label for="select_paradas_ida"><strong>Ruta:</strong> Seleccione las paradas</label>
                        <select multiple class="form-control @error('paradas_ida') is-invalid @enderror" name="paradas_ida[]" id="select_paradas_ida" required>
                            @if(old('paradas_ida'))
                                @foreach (old('paradas_ida') as $paradaId)
                                    @php
                                        $parada = $paradas->where('id', $paradaId)->first();
                                    @endphp
                                    @if($parada)
                                        <option value="{{ $parada->id }}" selected>{{ $parada->nombre }}</option>
                                    @endif
                                @endforeach
                            @endif
                            @foreach ($paradas as $parada)
                                @if (!collect(old('paradas_ida'))->contains($parada->id))
                                    <option value="{{ $parada->id }}">{{ $parada->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('paradas_ida')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12 mb-2">
                    <div class="border p-2">
                        <strong>Detalle de Retorno:</strong>
                        <textarea name="detalle_recorrido_retorno" class="form-control @error('detalle_recorrido_retorno') is-invalid @enderror" rows="4" required>{{ old('detalle_recorrido_retorno') }}</textarea>
                        @error('detalle_recorrido_retorno')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                        <label for="select_paradas_retorno"><strong>Ruta:</strong> Seleccione las paradas</label>
                        <select multiple class="form-control @error('paradas_retorno') is-invalid @enderror" name="paradas_retorno[]" id="select_paradas_retorno" required>
                            @if(old('paradas_retorno'))
                                @foreach (old('paradas_retorno') as $paradaId)
                                    @php
                                        $parada = $paradas->where('id', $paradaId)->first();
                                    @endphp
                                    @if($parada)
                                        <option value="{{ $parada->id }}" selected>{{ $parada->nombre }}</option>
                                    @endif
                                @endforeach
                            @endif
                            @foreach ($paradas as $parada)
                                @if (!collect(old('paradas_retorno'))->contains($parada->id))
                                    <option value="{{ $parada->id }}">{{ $parada->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('paradas_retorno')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label>Días Activos para vehículos:</label><br>
                       
                        <div class="form-group">
                            <label>Días Activos para vehículos:</label><br>
                           
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input @error('dias_activos') is-invalid @enderror" name="dias_activos[]" value="lunes" id="lunes" {{ in_array('lunes', old('dias_activos', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="lunes">Lunes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input @error('dias_activos') is-invalid @enderror" name="dias_activos[]" value="martes" id="martes" {{ in_array('martes', old('dias_activos', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="martes">Martes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input @error('dias_activos') is-invalid @enderror" name="dias_activos[]" value="miércoles" id="miércoles" {{ in_array('miércoles', old('dias_activos', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="miércoles">Miércoles</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input @error('dias_activos') is-invalid @enderror" name="dias_activos[]" value="jueves" id="jueves" {{ in_array('jueves', old('dias_activos', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="jueves">Jueves</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input @error('dias_activos') is-invalid @enderror" name="dias_activos[]" value="viernes" id="viernes" {{ in_array('viernes', old('dias_activos', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="viernes">Viernes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input @error('dias_activos') is-invalid @enderror" name="dias_activos[]" value="sábado" id="sábado" {{ in_array('sábado', old('dias_activos', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sábado">Sábado</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input @error('dias_activos') is-invalid @enderror" name="dias_activos[]" value="domingo" id="domingo" {{ in_array('domingo', old('dias_activos', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="domingo">Domingo</label>
                            </div>
                        
                            @error('dias_activos')
                                <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        

                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </div>
</form>
@endsection

@push('scriptsHeader')
<script src="{{ asset('assets/js/vendor/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('assets/js/vendor/forms/inputs/dual_listbox.min.js') }}"></script>
@endpush

@push('scriptsFooter')
<script>
    $('#vehiculos').multiselect({
        nonSelectedText: 'Seleccione vehículos',
        nSelectedText: 'seleccionadas',
        allSelectedText: 'Todas seleccionadas',
        numberDisplayed: 1,
        selectAllText: 'Seleccionar todo',
        filterPlaceholder: 'Buscar'
    });

    
    const listboxSortingElement = document.querySelector("#select_paradas_ida");
    const listboxSorting = new DualListbox(listboxSortingElement, {
        sortable: true,
        addButtonText: "Añadir",
        removeButtonText: "Eliminar",
        addAllButtonText: "Añadir todos",
        removeAllButtonText: "Eliminar todos",
        searchable: true,
        searchPlaceholder: "Buscar...",
        upButtonText: "<i class='ph-caret-up'></i> Subir",
        downButtonText: "<i class='ph-caret-down'></i> Bajar",
        availableTitle: "Opciones disponibles",
        selectedTitle: "Opciones seleccionadas",
        filterPlaceHolder: "Filtrar...",
        infoText: "Mostrando todo",
        infoTextFiltered: "<span class='text-danger'>Filtrado</span>",
        infoTextEmpty: "Lista vacía",
    });

    // Configuración similar para `select_paradas_retorno` si es necesario
    const listboxSortingElement2 = document.querySelector("#select_paradas_retorno");
    const listboxSorting2 = new DualListbox(listboxSortingElement2, {
        sortable: true,
        addButtonText: "Añadir",
        removeButtonText: "Eliminar",
        addAllButtonText: "Añadir todos",
        removeAllButtonText: "Eliminar todos",
        searchable: true,
        searchPlaceholder: "Buscar...",
        upButtonText: "<i class='ph-caret-up'></i> Subir",
        downButtonText: "<i class='ph-caret-down'></i> Bajar",
        availableTitle: "Opciones disponibles",
        selectedTitle: "Opciones seleccionadas",
        filterPlaceHolder: "Filtrar...",
        infoText: "Mostrando todo",
        infoTextFiltered: "<span class='text-danger'>Filtrado</span>",
        infoTextEmpty: "Lista vacía",
    });


</script>
@endpush
