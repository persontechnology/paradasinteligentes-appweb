@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('rutas.edit', $ruta) }}
@endsection

@section('content')
    <form action="{{ route('rutas.update', $ruta->id) }}" method="POST" onsubmit="actualizarInputSubrutas()">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-file-vue"></i>
                            </div>
                            <input type="text" name="nombre" value="{{ old('nombre', $ruta->nombre) }}"
                                class="form-control @error('nombre') is-invalid @enderror" placeholder="Nombre de ruta"
                                required autofocus>
                            <label>Nombre de ruta<i class="text-danger">*</i></label>
                            @error('nombre')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-article"></i>
                            </div>
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" placeholder="Descripción"
                                style="height: 100px;">{{ old('descripcion', $ruta->descripcion) }}</textarea>
                            <label>Descripción</label>
                            @error('descripcion')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <div class="form-floating form-control-feedback form-control-feedback-start">
                            <div class="form-control-feedback-icon">
                                <i class="ph ph-toggle-left"></i>
                            </div>
                            <select class="form-select @error('estado') is-invalid @enderror" name="estado">
                                <option value="ACTIVO" {{ old('estado', $ruta->estado) == 'ACTIVO' ? 'selected' : '' }}>
                                    ACTIVO</option>
                                <option value="INACTIVO" {{ old('estado', $ruta->estado) == 'INACTIVO' ? 'selected' : '' }}>
                                    INACTIVO</option>
                            </select>
                            <label>Estado</label>
                            @error('estado')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-1">
                        <p>Dibujar ruta</p>
                        <span class="badge bg-primary">{{ $paradas_activas }} ACTIVOS</span>
                        <span class="badge bg-danger">{{ $paradas_inactivas }} INACTIVOS</span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <select class="form-control select" data-placeholder="Buscar paradas..." onchange="buscarParada(this);">
                            <option></option>
                            @foreach ($paradas as $parada)
                            <option value="{{ $parada->id }}">{{ $parada->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="map"></div>
                    {{-- Input oculto para subrutas --}}
                    <input type="hidden" name="subrutas" id="subrutas-input" value="{{ old('subrutas', json_encode($subrutas)) }}">

                    <div class="info">
                        <p>Parada Inicial: <span id="parada-inicial">Ninguna</span></p>
                        <p>Parada Final: <span id="parada-final">Ninguna</span></p>
                        <p>Tiempo de Recorrido (minutos): <span id="tiempo-recorrido">N/A</span></p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
    </form>
@endsection

@push('scriptsHeader')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-polylinedecorator@1.5.1/dist/leaflet.polylineDecorator.min.js"></script>


    <style>
        #map {
            height: 600px;
            width: 100%;
        }
    </style>
@endpush

@push('scriptsFooter')
<script src="{{ asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script>
<script>
    const paradas = @json($paradas);
    let subrutas = @json($subrutas);
    let mapa = L.map('map').setView([-1.04315500, -78.59126700], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(mapa);


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
            mapa.removeLayer(searchMarker);
        }
        // Crear un nuevo marcador en la ubicación geocodificada
        searchMarker = L.marker(center)
            .addTo(mapa)
            .bindPopup(e.geocode.name)
            .openPopup();
        // Centrar el mapa en el marcador
        mapa.setView(center, 15); // Ajusta el zoom según tus necesidades
    })
    .addTo(mapa);





    let elementosDibujados = new L.FeatureGroup().addTo(mapa);
    let controlDeDibujo = new L.Control.Draw({
        edit: {
            featureGroup: elementosDibujados,
            edit: true,
            remove: true
        },
        draw: {
            polyline: false,
            polygon: false,
            circle: false,
            rectangle: false,
            marker: false
        }
    }).addTo(mapa);

    let polilineas = [];
    let paradaInicial = null;
    let paradaFinal = null;
    let marcadores = {};

    // Iconos personalizados usando FontAwesome
    const iconoActivo = L.divIcon({
        html: '<i class="fas fa-map-marker-alt" style="color: blue; font-size: 32px;"></i>',
        iconSize: [24, 24],
        className: 'my-div-icon'
    });

    const iconoInactivo = L.divIcon({
        html: '<i class="fas fa-map-marker-alt" style="color: red; font-size: 32px;"></i>',
        iconSize: [24, 24],
        className: 'my-div-icon'
    });

    paradas.forEach(parada => {
        let icono = parada.estado === 'INACTIVO' ? iconoInactivo : iconoActivo;
        let marcador = L.marker([parada.latitud, parada.longitud],{
            title: parada.nombre,
            icon:icono
        }).addTo(mapa)
        .bindPopup(parada.nombre)
        .bindTooltip(`<strong>${parada.nombre}</strong>`, { permanent: true, direction: 'right', offset: [10, 0] });

        marcadores[parada.id] = marcador; // Guardar el marcador en el objeto marcadores
        marcador.on('click', () => manejarClickEnParada(parada));
    });

    subrutas.forEach(subruta => {
        const inicio = subruta.parada_inicio;
        const final = subruta.parada_final;

        const coordenadas = subruta.coordenadas;
        let latlngs = coordenadas.map(coord => {
            if (Array.isArray(coord)) {
                return [parseFloat(coord[0]), parseFloat(coord[1])];
            } else {
                return [parseFloat(coord.lat), parseFloat(coord.lng)];
            }
        });

        // Asigna el ID de la subruta al Polyline
        let polilinea = L.polyline(latlngs, {
            color: 'blue',
            id: subruta.id // Asegúrate de que el ID está aquí
        }).addTo(mapa);
        elementosDibujados.addLayer(polilinea);
        polilinea.decorador = crearDecorador(polilinea);
    });

    function buscarParada(selector) {
        const paradaId = selector.value;
        const parada = paradas.find(p => p.id == paradaId);
        if (parada && marcadores[paradaId]) {
            mapa.setView([parada.latitud, parada.longitud], 16);
            marcadores[paradaId].openPopup();
        }
    }

    function manejarClickEnParada(parada) {
        if (!paradaInicial) {
            paradaInicial = parada;
            actualizarVisualizacion('parada-inicial', parada.nombre);
        } else if (!paradaFinal && parada.id !== paradaInicial.id) {
            paradaFinal = parada;
            actualizarVisualizacion('parada-final', parada.nombre);
            agregarRuta(paradaInicial, paradaFinal);
        }
    }

    function agregarRuta(inicial, final) {
        let latlngs = [
            [inicial.latitud, inicial.longitud],
            [final.latitud, final.longitud]
        ];
        let polilinea = L.polyline(latlngs, {
            color: 'blue'
        }).addTo(mapa);
        elementosDibujados.addLayer(polilinea);
        polilineas.push(polilinea);
        polilinea.decorador = crearDecorador(polilinea);

        $.confirm({
            title: `De: ${inicial.nombre}<br>Ha: ${final.nombre}`,
            content: `
            <div class="form-group">
                <label>Ingrese el tiempo de recorrido en minutos</label>
                <input type="time" class="form-control tiempo" required autofocus />
            </div>
        `,
            buttons: {
                guardar: {
                    text: 'GUARDAR',
                    btnClass: 'btn-blue',
                    action: function() {
                        const tiempoRecorrido = this.$content.find('.tiempo').val();
                        if (tiempoRecorrido) {
                            actualizarVisualizacion('tiempo-recorrido', tiempoRecorrido);
                            guardarSubruta(inicial, final, tiempoRecorrido, latlngs);
                        } else {
                            $.alert('Debe ingresar el tiempo de recorrido para completar la ruta.');
                            return false;
                        }
                    }
                },
                CANCELAR: function() {
                     // Remover la polilínea y su decorador del mapa
                    if (polilinea.decorador) {
                        mapa.removeLayer(polilinea.decorador);
                    }
                     // Remover la polilínea del mapa
                     mapa.removeLayer(polilinea);
                    // Eliminar la polilínea de la lista de polilíneas
                    polilineas = polilineas.filter(p => p !== polilinea);
                    // Reiniciar selección de paradas
                    paradaInicial = null;
                    paradaFinal = null;
                    actualizarVisualizacion('parada-inicial', 'Ninguna');
                    actualizarVisualizacion('parada-final', 'Ninguna');
                    actualizarVisualizacion('tiempo-recorrido', 'N/A');
                }
            }
        });
    }

    function crearDecorador(polilinea) {
        return L.polylineDecorator(polilinea, {
            patterns: [{
                offset: 25,
                repeat: 50,
                symbol: L.Symbol.arrowHead({
                    pixelSize: 15,
                    polygon: false,
                    pathOptions: {
                        stroke: true,
                        color: 'blue'
                    }
                })
            }]
        }).addTo(mapa);
    }

    function guardarSubruta(inicial, final, tiempo, coordenadas) {
        const subruta = {
            'parada_inicio_id': inicial.id,
            'parada_final_id': final.id,
            'tiempo_recorrido': tiempo,
            'coordenadas': coordenadas
        };
        subrutas.push(subruta);
        reiniciarSeleccionDeRuta(final);
    }

    function reiniciarSeleccionDeRuta(nuevaInicial) {
        paradaInicial = nuevaInicial;
        paradaFinal = null;
        actualizarVisualizacion('parada-inicial', nuevaInicial.nombre);
        actualizarVisualizacion('parada-final', 'Ninguna');
        actualizarVisualizacion('tiempo-recorrido', 'N/A');
    }

    function actualizarVisualizacion(idElemento, valor) {
        document.getElementById(idElemento).textContent = valor;
    }

    mapa.on(L.Draw.Event.EDITED, evento => {
        evento.layers.eachLayer(capa => {
            const latlngs = capa.getLatLngs();
            if (latlngs.length > 1) {
                const [inicio, final] = [latlngs[0], latlngs[latlngs.length - 1]];
                const paradaInicio = encontrarParadaMasCercana(inicio);
                const paradaFinal = encontrarParadaMasCercana(final);

                if (paradaInicio && paradaFinal) {
                    actualizarDecoradorDePolilinea(capa);

                    // Obtener la subruta actual para encontrar su tiempo de recorrido
                const subrutaActual = subrutas.find(s => 
                    s.parada_inicio_id === paradaInicio.id && 
                    s.parada_final_id === paradaFinal.id
                );

                const tiempoRecorridoActual = subrutaActual ? subrutaActual.tiempo_recorrido : '';

                solicitarRutaEditada(paradaInicio, paradaFinal, latlngs, tiempoRecorridoActual);

                } else {
                    $.alert("Debe haber una parada de inicio y una parada de final.");
                }
            }
        });
    });

    mapa.on(L.Draw.Event.DELETED, function(event) {
        var layers = event.layers;
        layers.eachLayer(function(layer) {
            // Obtener el id de la subruta desde las opciones de la capa
            const subrutaId = layer.options.id;

            if (subrutaId) {
                $.confirm({
                    title: 'Confirmar Eliminación',
                    content: `¿Está seguro de eliminar esta subruta?`,
                    type: 'red',
                    theme: 'modern',
                    icon: 'fa fa-trash fa-2x',
                    typeAnimated: true,
                    buttons: {
                        SI: {
                            btnClass: 'btn-red',
                            action: function() {
                                $.ajax({
                                    url: '/subrutas/' + subrutaId, // Usa el ID para eliminar la subruta
                                    method: 'DELETE',
                                    success: function(response) {
                                        new Noty({
                                            text: "Subruta eliminada correctamente.",
                                            type: "success"
                                        }).show();
                                        // Eliminar del array subrutas
                                        subrutas = subrutas.filter(s => s.id !== subrutaId);
                                        // Remover el decorador si existe
                                        if (layer.decorador) {
                                            mapa.removeLayer(layer.decorador);
                                        }
                                        // Remover la capa del mapa
                                        elementosDibujados.removeLayer(layer);
                                        // Ajustar la vista del mapa
                                        ajustarVistaMapa();
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Error al eliminar la subruta', error);
                                        try {
                                            var response = JSON.parse(xhr.responseText);
                                            new Noty({
                                                text: "Error: " + response.message,
                                                type: "error"
                                            }).show();
                                        } catch (e) {
                                            console.error('Error al procesar la respuesta del error:', e);
                                        }
                                    }
                                });
                            }
                        },
                        CANCELAR: function() {
                            // Revertir la eliminación visual si no se confirma
                            elementosDibujados.addLayer(layer);
                        }
                    }
                });
            }
        });
    });

    function ajustarVistaMapa() {
        if (elementosDibujados.getLayers().length > 0) {
            const bounds = elementosDibujados.getBounds();
            mapa.fitBounds(bounds);
        } else {
            // Si no hay capas dibujadas, restablecer la vista inicial
            mapa.setView([-1.04315500, -78.59126700], 15);
        }
    }

    function actualizarDecoradorDePolilinea(capa) {
        if (capa.decorador) {
            mapa.removeLayer(capa.decorador);
        }
        capa.decorador = crearDecorador(capa);
    }

    function solicitarRutaEditada(inicio, final, latlngs,tiempoRecorridoActual) {
        $.confirm({
            title: `De: ${inicio.nombre}<br>Ha: ${final.nombre}`,
            content: `
            <div class="form-group">
                <label>Ingrese el tiempo de recorrido editado en minutos</label>
                <input type="time" class="form-control tiempo" value="${tiempoRecorridoActual}" required />
            </div>
        `,
            buttons: {
                guardar: {
                    text: 'Guardar',
                    btnClass: 'btn-blue',
                    action: function() {
                        const tiempoRecorrido = this.$content.find('.tiempo').val();
                        if (tiempoRecorrido) {
                            actualizarVisualizacion('tiempo-recorrido', tiempoRecorrido);
                            actualizarSubruta({
                                'parada_inicio_id': inicio.id,
                                'parada_final_id': final.id,
                                'tiempo_recorrido': tiempoRecorrido,
                                'coordenadas': latlngs
                            });
                        } else {
                            $.alert('Debe ingresar el tiempo de recorrido para completar la ruta.');
                            return false;
                        }
                    }
                }
            }
        });
    }

    function encontrarParadaMasCercana(punto) {
        let paradaMasCercana = null;
        let distanciaMinima = Infinity;

        paradas.forEach(parada => {
            let distancia = punto.distanceTo([parada.latitud, parada.longitud]);
            if (distancia < distanciaMinima) {
                distanciaMinima = distancia;
                paradaMasCercana = parada;
            }
        });

        return paradaMasCercana;
    }

    function actualizarSubruta(subruta) {
        const index = subrutas.findIndex(s => s.parada_inicio_id === subruta.parada_inicio_id && s.parada_final_id ===
            subruta.parada_final_id);
        if (index !== -1) {
            subrutas[index] = subruta;
        } else {
            subrutas.push(subruta);
        }
    }

    function actualizarInputSubrutas() {
        document.getElementById('subrutas-input').value = JSON.stringify(subrutas);
    }

    $('.select').select2({
        placeholder: "Buscar paradas...",
        allowClear: true
    });
</script>

@endpush
