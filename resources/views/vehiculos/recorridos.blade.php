@extends('layouts.app')

@section('breadcrumb')
{{ Breadcrumbs::render('vehiculos.recorridos', $vehiculo) }}
@endsection

@section('content')




    <div class="card">
        <div class="card-header d-flex align-items-center py-0">
            
            <p><strong>Número de línea: </strong>{{ $vehiculo->numero_linea }} <br><strong>Código & placa:</strong> {{ $vehiculo->codigo }} - {{ $vehiculo->placa ?? 'Sin nombre' }} <br>
                <strong>Posiciones: </strong> <span id="contadorParada">{{ $posiciones->count() }}</span> <br>
                
                <span class="badge bg-primary"><i class="fa-solid fa-car-side"></i> N/A </span>
                <span class="badge bg-success"><i class="fa-solid fa-car-side"></i> IDA </span>
                <span class="badge bg-danger"><i class="fa-solid fa-car-side"></i> RETORNO </span>
                <span class="badge bg-warning"><i class="fa-solid fa-location-dot"></i> PARADAS </span>
                
            </p>
            
            <div class="ms-auto my-auto">
                
                <div class="btn-group">
                    <a href="{{ route('vehiculos.recorridos',$vehiculo->id-1) }}" class="btn btn-primary btn-icon">
                        <i class="ph ph-arrow-left"></i>
                    </a>
                    <a href="{{ route('vehiculos.recorridos',$vehiculo->id+1) }}" class="btn btn-primary btn-icon">
                        <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="card-header">
            
            <form method="GET" action="{{ route('vehiculos.recorridos', $vehiculo->id) }}">
                <div class="input-group">
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-calendar"></i>
                        </div>
                        <input type="datetime-local" name="fechaDesde" value="{{ old('fechaDesde', $fechaDesde) }}" class="form-control" placeholder="Desde" required>
                        <label>Desde</label>
                    </div>
                    <div class="form-floating form-control-feedback form-control-feedback-start">
                        <div class="form-control-feedback-icon">
                            <i class="ph ph-calendar"></i>
                        </div>
                        <input type="datetime-local" name="fechaHasta" value="{{ old('fechaHasta', $fechaHasta) }}" class="form-control" placeholder="Hasta" required>
                        <label>Hasta</label>
                    </div>
                    <button class="btn btn-primary" type="submit"><i class="ph ph-magnifying-glass me-1"></i></button>
                    <a href="{{ route('vehiculos.recorridos',$vehiculo) }}" class="btn btn-danger"><i class="ph ph-x me-1"></i></a>
                </div>
            </form>
        </div>
        <div class="card-body"> 
            <div id="map"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">LISTADO DE RECORRIDOS</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Coordenadas</th>
                            <th scope="col">Está en Ruta</th>
                            <th scope="col">Ruta</th>
                            <th scope="col">Vehículo</th>
                            <th scope="col">Velocidad</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Detalle</th>
                            <th scope="col">Dirección</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $posi_i=1;
                        @endphp
                        @foreach ($posiciones as $posi)
                        <tr>
                            <td>{{ $posi_i++ }}</td>
                            <td>{{ $posi->coordenadas }}</td>
                            <td>
                                @if ($posi->esta_ruta=='SI')
                                    <span class="badge bg-success">SI</span>
                                @else
                                    <span class="badge bg-danger">NO</span>    
                                @endif
                            </td>
                            <td>{{ $posi->tipoRuta->ruta->nombre??'' }}</td>
                            <td>{{ $posi->vehiculo->codigo }}</td>
                            <td>{{ $posi->velocidad }}</td>
                            <td>{{ $posi->created_at }}</td>
                            <td>{{ $posi->detalle }}</td>
                            <td>{{ $posi->direccion }}</td>
                        </tr>
                        @endforeach
    
                    </tbody>
                </table>
            </div>
            
        </div>
        <div class="card-footer text-muted">Footer</div>
    </div>

    
    
@endsection

@push('scriptsHeader')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
    #map {
        height: 100vh;
    }
    /* Estilos personalizados para los iconos */
    .coordenadas-icon {
        font-size: 20px;
    }
</style>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet-polylinedecorator@1.5.1/dist/leaflet.polylineDecorator.min.js"></script>

<link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
@endpush

@push('scriptsFooter')
<script>
    // Variables pasadas desde el controlador Laravel
    const vehiculo = @json($vehiculo);
    let posiciones = @json($posiciones);
    let markers = [];
    let polyline = null;
    let decorator = null;

    // Inicializar el mapa centrado en la ubicación actual del vehículo
    const posiVeh = JSON.parse(vehiculo.ubicacion_actual);
    const map = L.map('map', {
        fullscreenControl: true,
        fullscreenControlOptions: {
            position: 'topleft'
        }
    }).setView([posiVeh[0], posiVeh[1]], 13);

    // Añadir capa de mapa base desde OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    // Función para actualizar los marcadores, líneas y flechas
    function actualizarMarcadoresYLineas(posiciones) {
        const polylineCoords = [];

        // Eliminar marcadores existentes
        markers.forEach(marker => map.removeLayer(marker));
        markers = [];

        posiciones.forEach(posicion => {
            // Determinar el color del marcador según el tipo de ruta
            let marcadorColor = 'blue';  // Color por defecto
            if (posicion.tipo_ruta && posicion.tipo_ruta.tipo === 'IDA') {
                marcadorColor = 'green';
            } else if (posicion.tipo_ruta && posicion.tipo_ruta.tipo === 'RETORNO') {
                marcadorColor = 'red';
            }

            const posiCoor = JSON.parse(posicion.coordenadas);

            // Crear el marcador para cada posición
            const marker = L.marker([posiCoor[0], posiCoor[1]], {
                icon: L.divIcon({
                    className: 'coordenadas-icon',
                    html: `<i class="fa-solid fa-car-side" style="color:${marcadorColor};"></i>`
                })
            }).addTo(map)
                .bindPopup(() => {
                    let popupContent = `<b>Velocidad:</b> ${posicion.velocidad} km/h<br>
                                        <b>Está en ruta:</b> ${posicion.esta_ruta}<br>
                                        <b>Coordenadas: </b>${posiCoor}<br>
                                        <b>Fecha: </b>${posicion.created_at}<br>`;

                    // Agregar información del tipo de ruta si existe
                    if (posicion.tipo_ruta) {
                        popupContent += `<b>Tipo de Ruta:</b> ${posicion.tipo_ruta.tipo}<br>
                                         <b>Nombre de Ruta:</b> ${posicion.tipo_ruta.ruta.nombre}<br>`;
                    }

                    return popupContent;
                });

            markers.push(marker);
            polylineCoords.push([posiCoor[0], posiCoor[1]]);

            // Añadir marcador en las paradas si están cerca
            if (posicion.tipo_ruta) {
                posicion.tipo_ruta.paradas.forEach(parada => {
                    const paradaCoor = JSON.parse(parada.coordenadas);
                    const paradaLatLng = [paradaCoor[0], paradaCoor[1]];

                    const paradaMarker = L.marker(paradaLatLng, {
                        icon: L.divIcon({
                            className: 'custom-icon',
                            html: '<i class="fa-solid fa-location-dot text-warning fa-3x"></i>'
                        })
                    }).addTo(map)
                        .bindPopup(`<b>Parada:</b> ${parada.nombre}`)
                        .bindTooltip(`<strong>${parada.pivot.orden}-${posicion.tipo_ruta.tipo}</strong>`, {
                            permanent: true,
                            direction: 'right',
                            offset: [0, 0]
                        });

                    markers.push(paradaMarker);
                });
            }
        });

        // Eliminar la línea y decoraciones anteriores si existen
        if (polyline) {
            map.removeLayer(polyline);
        }
        if (decorator) {
            map.removeLayer(decorator);
        }

        // Dibujar las nuevas líneas poligonales con las coordenadas obtenidas
        polyline = L.polyline(polylineCoords, {
            color: 'blue', // Cambia este color según el tipo de ruta
        }).addTo(map);

        // Añadir nuevas flechas de dirección a la línea
        decorator = L.polylineDecorator(polyline, {
            patterns: [
                {
                    offset: 25, // Ajusta la distancia de la flecha desde el inicio de la línea
                    repeat: 50, // Ajusta la distancia entre flechas
                    symbol: L.Symbol.arrowHead({
                        pixelSize: 10, // Tamaño de la flecha
                        pathOptions: { color: 'blue', fillOpacity: 0.8 } // Estilo de la flecha
                    })
                }
            ]
        }).addTo(map);
    }

    // Agregar los marcadores, líneas y flechas iniciales
    actualizarMarcadoresYLineas(posiciones);

    document.addEventListener('DOMContentLoaded', function() {
        // Escuchar actualizaciones en tiempo real
        if (typeof window.Echo !== 'undefined') {
            window.Echo.channel('actualizar_recorrido_actual_vehiculo')
                .listen('ActualizarRecorridoActualVehiculo', (event) => {
                    if (event.posicion.vehiculo_id == {{ $vehiculo->id }}) {
                        console.log(event);

                        // Actualizar las posiciones con los nuevos datos
                        posiciones.push(event.posicion);
                        actualizarMarcadoresYLineas(posiciones);
                        $('#contadorParada').text(parseInt($('#contadorParada').text(), 10)+1)
                        
                    }
                });
        } else {
            console.error('Laravel Echo no está definido');
        }
    });

    
    


</script>
@endpush
