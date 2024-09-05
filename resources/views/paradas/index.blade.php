@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row mb-3">
                <label class="col-form-label col-lg-2">Buscar parada:</label>
                <div class="col-lg-10">
                    <select id="parada-select" onchange="buscarParada(this);" class="form-control multiselect" data-enable-filtering="true" data-enable-case-insensitive-filtering="true">
                        <option value="">Seleccione parada</option>
                        @foreach ($paradas as $pa)
                            <option value="{{ $pa->id }}">{{ $pa->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
        <div class="card-body">
            <div id="map" style="height: 600px;"></div>
        </div>
        <div class="card-footer text-muted">Footer</div>
    </div>
@endsection

@push('scriptsHeader')
<script src="{{ asset('assets/js/vendor/forms/selects/bootstrap_multiselect.js') }}"></script>


<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
<script src="https://cdn.jsdelivr.net/npm/leaflet-polylinedecorator@1.5.1/dist/leaflet.polylineDecorator.min.js"></script>

@endpush

@push('scriptsFooter')
<script>
    // Inicializa el multiselect
    $('#parada-select').multiselect({
        nonSelectedText: 'Seleccione parada',
        filterPlaceholder: 'Buscar'
    });

    var map = L.map('map',{
            fullscreenControl: true,
            fullscreenControlOptions: {
                position: 'topleft'
            }
        }).setView([-1.0446150076621883, -78.59029481937694], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    // Variable para almacenar el marcador de búsqueda
    var searchMarker;

    // Agregar el control de geocodificación al mapa
    var geocoder = L.Control.geocoder({
        defaultMarkGeocode: false // Para personalizar lo que ocurre al hacer clic en un resultado
    })
    .on('markgeocode', function(e) {
        var center = e.geocode.center;

        // Eliminar el marcador anterior si existe
        if (searchMarker) {
            map.removeLayer(searchMarker);
        }
        // Crear un nuevo marcador en la ubicación geocodificada
        searchMarker = L.marker(center)
            .addTo(map)
            .bindPopup(e.geocode.name)
            .openPopup();
        // Centrar el mapa en el marcador
        map.setView(center, 15); // Ajusta el zoom según tus necesidades
    })
    .addTo(map);

    // Capa donde se guardan las paradas editables
    var editableLayers = new L.FeatureGroup();
    map.addLayer(editableLayers);

    // Inicializar el control de dibujo
    var drawControl = new L.Control.Draw({
        draw: {
            polyline: false,
            polygon: false,
            circle: false,
            rectangle: false,
            marker: true,
            circlemarker: false
        },
        edit: {
            featureGroup: editableLayers, // Capa donde se guardan las paradas
            remove: true
        }
    });
    map.addControl(drawControl);

    // Cargar paradas existentes y ajustar el mapa
    var paradas = @json($paradas);
    var bounds = new L.LatLngBounds(); // Crear un bounds para ajustar la vista del mapa
    var paradasMarkers = {}; // Objeto para almacenar marcadores de paradas por ID

    paradas.forEach(function(parada) {
        var coords = JSON.parse(parada.coordenadas);

        var marker = L.marker([coords[0], coords[1]], {
            paradaId: parada.id,
            nombre: parada.nombre // Asociar nombre de la parada al marcador
        }).addTo(editableLayers)
        .bindPopup(parada.nombre)
        .bindTooltip(`<strong>${parada.nombre}</strong>`, { permanent: true, direction: 'right', offset: [0, 0] });

        bounds.extend(marker.getLatLng()); // Extender el bounds para incluir este marcador

        // Guardar el marcador en el objeto paradasMarkers
        paradasMarkers[parada.id] = marker;
    });

    // Ajustar el mapa para que muestre todas las paradas
    if (paradas.length > 0) {
        map.fitBounds(bounds);
    }

    // Función para buscar y centrar en la parada seleccionada
    function buscarParada(selectElement) {
        var paradaId = selectElement.value;

        if (paradaId && paradasMarkers[paradaId]) {
            var latLng = paradasMarkers[paradaId].getLatLng();
            map.setView(latLng, 18); // Centrar el mapa en la ubicación de la parada seleccionada con un zoom de 18
            paradasMarkers[paradaId].openPopup(); // Abrir el popup del marcador seleccionado
        }
    }

    // Manejar la creación de nuevas paradas
    map.on(L.Draw.Event.CREATED, function(event) {
        var layer = event.layer;
        var coords = layer.getLatLng();

        var nombre = prompt("Ingrese el nombre de la parada:");
        if (nombre) {
            $.ajax({
                url: '{{ route("paradas.store") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    nombre: nombre,
                    coordenadas: [coords.lat, coords.lng]
                },
                success: function(response) {
                    layer.bindPopup(nombre).openPopup();
                    editableLayers.addLayer(layer);
                    new Noty({
                        text: 'Parada creada exitosamente',
                        type: "success"
                    }).show();

                    // Añadir la nueva parada a los bounds y reajustar el mapa
                    bounds.extend(layer.getLatLng());
                    map.fitBounds(bounds);
                },
                error: function(xhr) {
                    $.alert('Error al crear la parada: ' + xhr.responseText);
                    map.removeLayer(layer); // Eliminar el marcador si la creación falla
                }
            });
        }
    });

    // Manejar la eliminación de paradas
    map.on('draw:deleted', function(event) {
        var layers = event.layers;
        layers.eachLayer(function(layer) {
            var paradaId = layer.options.paradaId;
            if (paradaId) {
                $.ajax({
                    url: '{{ url("/paradas/") }}/' + paradaId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        new Noty({
                            text: 'Parada eliminada exitosamente',
                            type: "success"
                        }).show();
                    },
                    error: function(xhr) {
                        $.alert('Error al eliminar la parada: ' + xhr.responseText);
                    }
                });
            }
        });
    });

    // Manejar la edición de paradas existentes
    map.on('draw:edited', function(event) {
        var layers = event.layers;
        layers.eachLayer(function(layer) {
            var paradaId = layer.options.paradaId;
            var coords = layer.getLatLng();
            var currentName = layer.options.nombre;

            if (paradaId) {
                var newName = prompt("Modifique el nombre de la parada:", currentName);

                if (newName !== null) { // Solo si se modifica el nombre
                    $.ajax({
                        url: '{{ url("/paradas/") }}/' + paradaId + '/actualizar-coordenadas',
                        type: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            nombre: newName,
                            coordenadas: [coords.lat, coords.lng]
                        },
                        success: function(response) {
                            layer.bindPopup(newName).openPopup();
                            layer.options.nombre = newName; // Actualizar el nombre en las opciones del marcador
                            new Noty({
                                text: 'Parada actualizada exitosamente',
                                type: "success"
                            }).show();
                        },
                        error: function(xhr) {
                            $.alert('Error al actualizar la parada: ' + xhr.responseText);
                        }
                    });
                }
            }
        });
    });

</script>
@endpush



