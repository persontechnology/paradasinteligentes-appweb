@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <p>Vehículo: {{ $vehiculo->codigo }} - {{ $vehiculo->placa ?? 'Sin nombre' }}</p>
            <h1>{{ $vehiculo->id }}</h1>
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
    var ubicacionActual = JSON.parse(vehiculoPosicion.ubicacion_actual);
    var lat = ubicacionActual[0];
    var lng = ubicacionActual[1];

    var map = L.map('map').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    var marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(map);

    map.on('click', function(e) {
        var newLatLng = e.latlng;
        marker.setLatLng(newLatLng);

        $.ajax({
            url: '{{ route("vehiculo.updateLocation", $vehiculo->id) }}', // Ruta que procesará la actualización
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                lat: newLatLng.lat,
                lng: newLatLng.lng
            },
            success: function(response) {
                new Noty({
                    text: response.message,
                    type: "success"
                }).show();
            },
            error: function(xhr, status, error) {
                new Noty({
                    text: xhr.responseText,
                    type: "error"
                }).show();
            }
        });
    });
</script>
@endpush
