<p>{{ $pv->tipoRuta->ruta->nombre??'' }}</p>

@if (isset($pv->tipoRuta->tipo) && $pv->tipoRuta->tipo==='IDA')
    <span class="badge bg-primary">{{ $pv->tipoRuta->tipo??'' }}</span>
@else
    <span class="badge bg-danger">{{ $pv->tipoRuta->tipo??'' }}</span>
@endif
