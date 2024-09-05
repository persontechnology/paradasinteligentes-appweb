<div class="dropdown">
    <a href="#" class="text-body dropdown-toggle" data-bs-toggle="dropdown">
        <i class="ph-gear"></i>
    </a>

    <div class="dropdown-menu">
        <a href="{{ route('vehiculos.ubicacion',$vehiculo) }}" class="dropdown-item">
            <i class="ph ph-map-pin me-2"></i>
            Ver ubicación
        </a>
        <a href="{{ route('vehiculos.horario',$vehiculo) }}" class="dropdown-item">
            <i class="ph ph-calendar-blank me-2"></i>
            Horario
        </a>
        <div class="dropdown-header">Optiones</div>
        <a href="{{ route('vehiculos.edit',$vehiculo) }}" class="dropdown-item">
            <i class="ph-pen me-2"></i>
            Editar
        </a>
      
        <a href="{{ route('vehiculos.destroy',$vehiculo->id) }}" data-msg="{{ $vehiculo->placa }}" onclick="event.preventDefault(); eliminar(this)" class="dropdown-item">
            <i class="ph ph-trash me-2"></i>
            Eliminar
        </a>
        
    </div>
</div>