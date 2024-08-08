@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('paradas.index') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="mb-1">
                <span class="badge bg-primary">{{ $paradas_activas }} ACTIVOS</span>
                <span class="badge bg-danger">{{ $paradas_inactivas }} INACTIVOS</span>
            </div>
            
            <div class="input-group">
                <select class="form-control select" data-width="auto;" data-placeholder="Buscar paradas..." onchange="buscarParada(this);">
                    <option></option>
                    @foreach ($paradas as $parada)
                    <option value="{{ $parada->id }}">{{ $parada->nombre }}</option>
                    @endforeach
                </select>
                <button type="button" onclick="abrir_modal_paradas(this);" class="btn btn-success">Ver en listado</button>
            </div>
        </div>
        <div class="card-body">
            <div id="mapid"></div>
        </div>
    </div>

      <!-- paradas modal -->
	<div id="modal_paradas" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Listado de paradas</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<div class="modal-body">
					<div class="table-responsive">
                        {{ $dataTable->table() }}
                    </div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<!-- /paradas modal -->
@endsection

@push('scriptsHeader')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <style>
        #mapid {
            height: 600px;
        }
    </style>
@endpush

@push('scriptsFooter')
    <script src="{{ asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script>
    
    <script>
        
        var paradas=@json($paradas);

        var map = L.map('mapid').setView([40.712776, -74.005974], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap'
        }).addTo(map);




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



        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            edit: {
                featureGroup: drawnItems
            },
            draw: {
                polygon: true,
                marker: false,
                circle: false,
                rectangle: false,
                polyline: false,
                circlemarker: false
            }
        });
        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            var layer = e.layer;

            $.confirm({
                title: 'Parada',
                content: '' +
                    '<div class="form-group">' +
                    '<label>Ingrese nombre de parada</label>' +
                    '<input type="text" placeholder="" class="nombre_parada form-control" value="" required />' +
                    '</div>',
                buttons: {
                    guardar: {
                        text: 'Guadar',
                        btnClass: 'btn-blue',
                        action: function() {
                            var nombre = this.$content.find('.nombre_parada').val();
                            if (nombre) {
                                var geojson = layer.toGeoJSON();
                                if (geojson.geometry && geojson.geometry.coordinates.length > 0) {
                                    var latlngs = geojson.geometry.coordinates[0].map(coord => [coord[
                                        1], coord[0]
                                    ]);
                                    if (latlngs.length > 0) {
                                        $.ajax({
                                            url: '/api-paradas',
                                            method: 'POST',
                                            contentType: 'application/json',
                                            data: JSON.stringify({
                                                nombre: nombre,
                                                latitud: latlngs[0][0],
                                                longitud: latlngs[0][1],
                                                geocerca: JSON.stringify(latlngs)
                                            }),
                                            success: function(response) {

                                                new Noty({
                                                    text: "" + response.message,
                                                    type: "alert"
                                                }).show();

                                                layer.bindPopup(nombre).openPopup();
                                                drawnItems.addLayer(layer);
                                                fitMapToBounds();// Ajustar la vista del mapa
                                            },
                                            error: function(xhr, status, error) {
                                                console.error('Error al guardar la parada',
                                                    error);
                                                try {
                                                    // Intenta analizar el error como JSON
                                                    var response = JSON.parse(xhr
                                                        .responseText);
                                                    // Muestra el mensaje en la consola
                                                    new Noty({
                                                        text: "" + response.message,
                                                        type: "error"
                                                    }).show();

                                                } catch (e) {
                                                    // Si la respuesta no es JSON o hay otro error
                                                    console.error(
                                                        'Error al procesar la respuesta del error:',
                                                        e);
                                                }

                                            }
                                        });
                                    }
                                }
                            } else {
                                $.alert('Se requiere un nombre para la parada.');
                                layer.remove();
                                return false;
                            }

                        }
                    },
                    cancelar: function() {
                        layer.remove();
                    }
                }
            });

        });

        map.on('draw:edited', function(e) {
            var layers = e.layers;
            layers.eachLayer(function(layer) {

                $.confirm({
                    title: 'Parada',
                    content: '' +
                        '<div class="form-group">' +
                        '<label>Ingrese nombre de parada</label>' +
                        '<input type="text" placeholder="" class="nombre_parada form-control" value="' +layer.options.nombre + '" required />' +
                        '</div>',
                    buttons: {
                        guardar: {
                            text: 'Guadar',
                            btnClass: 'btn-blue',
                            action: function() {
                                var nombre = this.$content.find('.nombre_parada').val();
                                if (nombre) {
                                    var geojson = layer.toGeoJSON();
                                    var latlngs = geojson.geometry.coordinates[0].map(coord => [
                                        coord[1], coord[0]
                                    ]);
                                    var id = layer.options.id; // Asegúrate de asignar un ID al crear el polígono

                                    $.ajax({
                                        url: '/api-paradas/' + id,
                                        method: 'PUT',
                                        contentType: 'application/json',
                                        data: JSON.stringify({
                                            nombre: nombre,
                                            latitud: latlngs[0][0],
                                            longitud: latlngs[0][1],
                                            geocerca: JSON.stringify(latlngs)
                                        }),
                                        success: function(response) {

                                            new Noty({
                                                text: "" + response.message,
                                                type: "alert"
                                            }).show();
                                            layer.bindPopup(nombre).openPopup();
                                            fitMapToBounds
                                        (); // Ajustar la vista del mapa
                                        },
                                        error: function(xhr, status, error) {
                                            console.error(
                                                'Error al actualizar la parada',
                                                error);
                                            try {
                                                // Intenta analizar el error como JSON
                                                var response = JSON.parse(xhr
                                                    .responseText);
                                                // Muestra el mensaje en la consola
                                                new Noty({
                                                    text: "" + response
                                                        .message,
                                                    type: "error"
                                                }).show();

                                            } catch (e) {
                                                // Si la respuesta no es JSON o hay otro error
                                                console.error(
                                                    'Error al procesar la respuesta del error:',
                                                    e);
                                            }

                                        }
                                    });
                                }

                            }
                        },
                        cancelar: function() {

                        }
                    }
                });


            });
        });

        map.on('draw:deleted', function(e) {
            var layers = e.layers;
            layers.eachLayer(function(layer) {
                var id = layer.options.id; // Asegúrate de que cada capa tenga un ID
                var nombre = layer.options.nombre;
                $.confirm({
                    title: 'Está seguro de eliminar.!',
                    content: "" + nombre,
                    type: 'red',
                    theme: 'modern',
                    icon: 'fa fa-trash fa-2x',
                    typeAnimated: true,
                    buttons: {
                        SI: {
                            action: function() {
                                $.ajax({
                                    url: '/api-paradas/' + id,
                                    method: 'DELETE',
                                    success: function() {
                                        new Noty({
                                            text: "Parada eliminado.",
                                            type: "alert"
                                        }).show();
                                        fitMapToBounds
                                    (); // Ajustar la vista del mapa
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Error al eliminar la parada',
                                            error);
                                        try {
                                            // Intenta analizar el error como JSON
                                            var response = JSON.parse(xhr
                                                .responseText);
                                            // Muestra el mensaje en la consola
                                            new Noty({
                                                text: "" + response.message,
                                                type: "error"
                                            }).show();

                                        } catch (e) {
                                            // Si la respuesta no es JSON o hay otro error
                                            console.error(
                                                'Error al procesar la respuesta del error:',
                                                e);
                                        }
                                    }
                                });
                            }
                        },
                        NO: function() {
                            layer.bindPopup(nombre).openPopup();
                            drawnItems.addLayer(layer);
                        }
                    }
                });

            });
        });

        // Objeto para almacenar las capas de las paradas por su ID
        var paradaLayers = {};
        function fetchParadas(){
            
            paradas.forEach(function(parada) {
                if (parada.geocerca) {
                    var coordinates = JSON.parse(parada.geocerca);
                    var polygon = L.polygon(coordinates, {
                        color: parada.estado=='ACTIVO'?'blue':'red',
                        id: parada.id, // Guardar ID para operaciones futuras
                        nombre: parada.nombre
                    }).bindPopup(parada.nombre)
                    .bindTooltip(`<strong>${parada.nombre}</strong>`, { permanent: true, direction: 'right', offset: [10, 0] });
                    drawnItems.addLayer(polygon);
                    // Almacenar la capa en el objeto paradaLayers
                    paradaLayers[parada.id] = polygon;
                }
            });
            fitMapToBounds();
        }

        

        function fitMapToBounds() {
            if (drawnItems.getLayers().length > 0) {
                var bounds = drawnItems.getBounds();
                map.fitBounds(bounds);
            }
        }

        $(document).ready(function() {
            fetchParadas();
        });

        function buscarParada(selectElement) {
            // Obtiene el ID de la parada seleccionada
            var selectedId = selectElement.value;
            mostrarParadaMapa(selectedId);
        }
        function mostrarParadaMapa(idParada){
            // Busca la parada por ID usando paradaLayers
            var paradaLayer = paradaLayers[idParada];

            if (paradaLayer) {
                // Calcula el centro de la geocerca
                var bounds = paradaLayer.getBounds();
                var center = bounds.getCenter();

                // Centra el mapa en la parada seleccionada con un zoom adecuado
                map.setView(center, 16); // Ajusta el nivel de zoom según sea necesario
                
                // Abre el popup de la capa
                paradaLayer.openPopup();
            } else {
                
                // Muestra el mensaje en la consola
                new Noty({
                    text: "Parada no encontrada.",
                    type: "error"
                }).show();
            }
        }

        function abrir_modal_paradas(){
            $('#modal_paradas').modal('show');
        }
        function cerrar_modal_paradas(){
            $('#modal_paradas').modal('hide');
        }

        function cambiarEstadoParada(checkbox) {
            var paradaId = checkbox.value;
            var nuevoEstado = checkbox.checked ? 'ACTIVO' : 'INACTIVO';

            $.ajax({
                url: '/api-paradas/' + paradaId + '/estado',
                method: 'PATCH',
                contentType: 'application/json',
                data: JSON.stringify({
                    estado: nuevoEstado
                }),
                success: function(response) {
                    new Noty({
                        text: "" + response.message,
                        type: "alert"
                    }).show();

                    // Actualiza el texto de la etiqueta para reflejar el nuevo estado
                    var label = document.querySelector('label[for="estado_parada_' + paradaId + '"]');
                    label.textContent = nuevoEstado;
                    var paradaLayer = paradaLayers[paradaId];
                    paradaLayer.setStyle({ color: response.color });

                },
                error: function(xhr, status, error) {
                    console.error('Error al actualizar el estado de la parada:', error);
                    try {
                        var response = JSON.parse(xhr.responseText);
                        new Noty({
                            text: "Error: " + response.message,
                            type: "error"
                        }).show();
                    } catch (e) {
                        console.error('Error al procesar la respuesta del error:', e);
                    }

                    // Revertir el cambio en el checkbox si ocurre un error
                    checkbox.checked = !checkbox.checked;
                }
            });
        }


        $('.select').select2();

    </script>
    {{ $dataTable->scripts() }}
@endpush
