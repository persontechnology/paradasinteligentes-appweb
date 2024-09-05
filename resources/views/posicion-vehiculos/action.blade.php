<div class="dropdown">
    <a href="#" class="text-body dropdown-toggle" data-bs-toggle="dropdown">
        <i class="ph-gear"></i>
    </a>

    <div class="dropdown-menu">
        
        <div class="dropdown-header">Optiones</div>

        <a href="{{ route('poisicion-vehiculos.destroy',$pv->id) }}" data-msg="Posición de vehículo: {{ $pv->vehiculo->codigo }}" onclick="event.preventDefault(); eliminar(this)" class="dropdown-item">
            <i class="ph ph-trash me-2"></i>
            Eliminar
        </a>
        
    </div>
</div>