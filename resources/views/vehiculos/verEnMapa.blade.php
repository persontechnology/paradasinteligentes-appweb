@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('vehiculos.veren.mapa') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row mb-3">
                <label class="col-form-label col-lg-2">Buscar vehículo:</label>
                <div class="col-lg-10">
                    <select id="vehiculo-select" onchange="buscarVehiculo(this);" class="form-control multiselect" data-enable-filtering="true" data-enable-case-insensitive-filtering="true">
                        <option value="">Seleccione vehículo</option>
                        @foreach ($vehiculos as $ve)
                            <option value="{{ $ve->id }}">{{ $ve->numero_linea }}-{{ $ve->codigo }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div id="map"></div>
    </div>
@endsection

@push('scriptsHeader')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
<script src="{{ asset('assets/js/vendor/forms/selects/bootstrap_multiselect.js') }}"></script>
<style>
    #map {
        height: 100vh;
    }
</style>
@endpush

@push('scriptsFooter')

<script>
    // Inicializa el multiselect
    $('#vehiculo-select').multiselect({
        nonSelectedText: 'Seleccione vehículo',
        filterPlaceholder: 'Buscar'
    });

    var map = L.map('map',{
            fullscreenControl: true, // Activa el control de pantalla completa
            fullscreenControlOptions: {
                position: 'topleft' // Puedes cambiar la posición a 'topleft', 'topright', 'bottomleft', o 'bottomright'
            }
        }).setView([0, 0], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    var vehiculosMarkers = {};
    var bounds = L.latLngBounds(); // Para almacenar las posiciones de los vehículos y ajustar el mapa

    // Inicializa los marcadores de vehículos en el mapa
    var vehiculos = @json($vehiculos);
    vehiculos.forEach(function(vehiculo) {
        var coords = JSON.parse(vehiculo.ubicacion_actual);
        var lat = coords[0];
        var lon = coords[1];

        // Añade la posición del vehículo a los límites (bounds)
        var latLng = L.latLng(lat, lon);
        bounds.extend(latLng);

        vehiculosMarkers[vehiculo.id] = L.marker(latLng).addTo(map)
            .bindPopup(`
                <b># Linea:</b> ${vehiculo.numero_linea || 'N/A'}<br>
                <b>Código:</b> ${vehiculo.codigo || ''}<br>
                <b>Placa:</b>${vehiculo.placa || ''}<br>
                <b>Coordenadas:</b> ${lat}, ${lon}`)
            .bindTooltip(`<strong>${vehiculo.numero_linea || vehiculo.codigo}</strong>`, { permanent: true, direction: 'right', offset: [0, 0] });
    });

    // Ajusta el mapa para mostrar todos los vehículos inicialmente
    if (bounds.isValid()) {
        map.fitBounds(bounds);
    }

    // Funcionalidad para centrar el mapa en el vehículo seleccionado
    function buscarVehiculo(arg){
        var vehiculoId = $(arg).val();
        if (vehiculoId && vehiculosMarkers[vehiculoId]) {
            var latLng = vehiculosMarkers[vehiculoId].getLatLng();
            map.setView(latLng, 16); // Centrar el mapa en la ubicación del vehículo seleccionado con un zoom de 16
            vehiculosMarkers[vehiculoId].openPopup(); // Abrir el popup del marcador seleccionado
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Escuchar actualizaciones en tiempo real
        if (typeof window.Echo !== 'undefined') {
            window.Echo.channel('actualizar_posicion_actual_vehiculo')
                .listen('ActualizarPosicionActualVehiculo', (e) => {
                    var vehiculo = e.vehiculo;
                    var coords = JSON.parse(vehiculo.ubicacion_actual);
                    var lat = coords[0];
                    var lon = coords[1];

                    var latLng = L.latLng(lat, lon);

                    if (vehiculosMarkers[vehiculo.id]) {
                        // Actualizar la posición del marcador sin ajustar el mapa
                        vehiculosMarkers[vehiculo.id].setLatLng(latLng);
                    } else {
                        // Añadir el marcador si no existe
                        vehiculosMarkers[vehiculo.id] = L.marker(latLng).addTo(map)
                            .bindPopup(`
                                <b># Linea:</b> ${vehiculo.numero_linea || 'N/A'}<br>
                                <b>Código:</b> ${vehiculo.codigo || ''}<br>
                                <b>Placa:</b>${vehiculo.placa || ''}<br>
                                <b>Coordenadas:</b> ${lat}, ${lon}`)
                            .bindTooltip(`<strong>${vehiculo.numero_linea || vehiculo.codigo}</strong>`, { permanent: true, direction: 'right', offset: [0, 0] });
                    }
                });
        } else {
            console.error('Laravel Echo no está definido');
        }
    });
</script>
@endpush
