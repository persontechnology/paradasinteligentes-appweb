@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('vehiculos.ubicacion', $vehiculo) }}
@endsection

@section('breadcrumb_elements')
<div class="collapse d-lg-block ms-lg-auto" id="breadcrumb_elements">
    <div class="d-lg-flex mb-2 mb-lg-0">
        <a href="{{ route('vehiculos.recorridos', $vehiculo) }}" class="d-flex align-items-center text-body py-2">
            <i class="ph ph-path me-1"></i>
            Recorridos
        </a>
    </div>
</div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <p>Vehículo: {{ $vehiculo->codigo }} - {{ $vehiculo->placa ?? 'Sin nombre' }}</p>
        </div>
        <div class="card-body">
            <div id="map"></div>
        </div>
    </div>
@endsection

@push('scriptsHeader')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
    #map {
        height: 100vh;
    }
</style>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
@endpush

@push('scriptsFooter')
<script>
    var vehiculoPosicion = @json($vehiculo);
    
    // Parsear las coordenadas desde la cadena JSON
    var ubicacionActual = JSON.parse(vehiculoPosicion.ubicacion_actual);
    var latV = ubicacionActual[0];
    var lngV = ubicacionActual[1];
    
    // Inicializar el mapa centrado en la posición del vehículo
    var map = L.map('map').setView([latV, lngV], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Agregar un marcador para la ubicación del vehículo
    var marker = L.marker([latV, lngV]).addTo(map)
        .bindPopup(`
            <b># Linea:</b> ${vehiculoPosicion.numero_linea || 'N/A'}<br>
            <b>Código:</b> ${vehiculoPosicion.codigo || ''}<br>
            <b>Placa:</b>${vehiculoPosicion.placa || ''}<br>
            <b>Coordenadas:</b> ${latV}, ${lngV}`)
        .openPopup();

    // Añadir control de pantalla completa
    L.control.fullscreen().addTo(map);

    document.addEventListener('DOMContentLoaded', function() {
        // Escuchar actualizaciones en tiempo real
        if (typeof window.Echo !== 'undefined') {
            
            window.Echo.channel('actualizar_posicion_actual_vehiculo')
                .listen('ActualizarPosicionActualVehiculo', (event) => {
                    
                    if(event.vehiculo.id === {{ $vehiculo->id }}){
                        // Parsear las nuevas coordenadas
                        var nuevaUbicacion = JSON.parse(event.vehiculo.ubicacion_actual);
                        var nuevoLat = nuevaUbicacion[0];
                        var nuevoLng = nuevaUbicacion[1];

                        // Mover el marcador a la nueva ubicación y centrar el mapa
                        marker.setLatLng([nuevoLat, nuevoLng])
                            .bindPopup(`
                                <b># Linea:</b> ${event.vehiculo.numero_linea || 'N/A'}<br>
                                <b>Código:</b> ${event.vehiculo.codigo || ''}<br>
                                <b>Placa:</b>${event.vehiculo.placa || ''}<br>
                                <b>Coordenadas:</b> ${nuevoLat}, ${nuevoLng}`)
                            .openPopup();

                        // Centrar el mapa en la nueva ubicación del marcador
                        map.panTo([nuevoLat, nuevoLng]);
                    }

                });
        } else {
            console.error('Laravel Echo no está definido');
        }
    });
</script>
@endpush
