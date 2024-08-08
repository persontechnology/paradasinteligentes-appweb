<div class="dropdown">
    <a href="#" class="text-body dropdown-toggle" data-bs-toggle="dropdown">
        <i class="ph-gear"></i>
    </a>

    <div class="dropdown-menu">
        <a href="{{ route('rutas.show',$ruta) }}" class="dropdown-item">
            <i class="ph ph-eye me-2"></i>
            Detalle
        </a>

        <div class="dropdown-header">Optiones</div>
        <a href="{{ route('rutas.edit',$ruta) }}" class="dropdown-item">
            <i class="ph-pen me-2"></i>
            Editar
        </a>
      
        <a href="{{ route('rutas.destroy',$ruta->id) }}" data-msg="{{ $ruta->nombre }}" onclick="event.preventDefault(); eliminar(this)" class="dropdown-item">
            <i class="ph ph-trash me-2"></i>
            Eliminar
        </a>
        
    </div>
</div>