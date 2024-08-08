<div class="form-check form-switch mb-2">
    <input type="checkbox" class="form-check-input" value="{{ $parada->id }}" onchange="cambiarEstadoParada(this)" id="estado_parada_{{ $parada->id }}" {{ $parada->estado=='ACTIVO'?'checked':'' }}>
    <label class="form-check-label" for="estado_parada_{{ $parada->id }}">{{ $parada->estado }}</label>
</div>