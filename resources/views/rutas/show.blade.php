@extends('layouts.app')

@section('content')
	
	<div class="card">
		<div class="card-header">
			<p>
				<strong>{{ $ruta->nombre }}</strong> {{ $ruta->tipoRutaIda->tipo}} <br>
				<strong>Detalle del recorrido IDA:</strong> {{ $ruta->tipoRutaIda->detalle_recorrido }}
			</p> 
			
			 
		</div>
		<div id="mapaIda"></div>
	</div>

	<div class="card">
		<div class="card-header">
			<p>
				<strong>{{ $ruta->nombre }}</strong> {{ $ruta->tipoRutaRetorno->tipo}} <br>
				<strong>Detalle del recorrido RETORNO:</strong> {{ $ruta->tipoRutaRetorno->detalle_recorrido }}
			</p>
			
			
		</div>
		<div id="mapaRetorno"></div>
	</div>
	
	
	
@endsection



@push('scriptsHeader')

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />
	<script src="https://cdn.jsdelivr.net/npm/leaflet-polylinedecorator@1.5.1/dist/leaflet.polylineDecorator.min.js"></script>

    <style>
        #mapaIda, #mapaRetorno {
            height: 400px;
            margin-bottom: 20px;
        }
        
    </style>
@endpush

@push('scriptsFooter')
    
	<script>
		
		// Inicialización de los datos
		var paradasIda = @json($paradasIda);
		var paradasRetorno = @json($paradasRetorno);
		var coordenadasIdas = @json($coordenadasIdas);
		var coordenadasRetorno = @json($coordenadasRetorno);

		// Variables para almacenar las nuevas coordenadas
		var nuevasCoordenadasIda = coordenadasIdas.slice();
		var nuevasCoordenadasRetorno = coordenadasRetorno.slice();

		var lineaIdaModificada = false;
		var lineaRetornoModificada = false;

		
		var coordenadasIdaMapa = JSON.parse(paradasIda[0]['coordenadas']);
		var longitudMapaIda = [parseFloat(coordenadasIdaMapa[0]), parseFloat(coordenadasIdaMapa[1])];

		var coordenadasRetornoMapa = JSON.parse(paradasRetorno[0]['coordenadas']);
		var longitudMapaRetorno = [parseFloat(coordenadasRetornoMapa[0]), parseFloat(coordenadasRetornoMapa[1])];

		
		// Configuración de los mapas
		var mapaIda = L.map('mapaIda',{
            fullscreenControl: true, // Activa el control de pantalla completa
            fullscreenControlOptions: {
                position: 'topleft' // Puedes cambiar la posición a 'topleft', 'topright', 'bottomleft', o 'bottomright'
            }
        }).setView(longitudMapaIda || [0, 0], 13);

		// Variable para almacenar el marcador de búsqueda
		var searchMarkerIda;
		// Agregar el control de geocodificación al mapa
		var geocoder = L.Control.geocoder({
			defaultMarkGeocode: false // Para personalizar lo que ocurre al hacer clic en un resultado
		})
		.on('markgeocode', function(e) {
			var center = e.geocode.center;

			// Eliminar el marcador anterior si existe
			if (searchMarkerIda) {
				map.removeLayer(searchMarkerIda);
			}
			// Crear un nuevo marcador en la ubicación geocodificada
			searchMarkerIda = L.marker(center)
				.addTo(map)
				.bindPopup(e.geocode.name)
				.openPopup();
			// Centrar el mapa en el marcador
			mapaIda.setView(center, 15); // Ajusta el zoom según tus necesidades
		})
		.addTo(mapaIda);


		
		var mapaRetorno = L.map('mapaRetorno',{
            fullscreenControl: true, // Activa el control de pantalla completa
            fullscreenControlOptions: {
                position: 'topleft' // Puedes cambiar la posición a 'topleft', 'topright', 'bottomleft', o 'bottomright'
            }
        }).setView(longitudMapaRetorno || [0, 0], 13);

		// Variable para almacenar el marcador de búsqueda
		var searchMarkerRetorno;
		// Agregar el control de geocodificación al mapa
		var geocoder = L.Control.geocoder({
			defaultMarkGeocode: false // Para personalizar lo que ocurre al hacer clic en un resultado
		})
		.on('markgeocode', function(e) {
			var center = e.geocode.center;

			// Eliminar el marcador anterior si existe
			if (searchMarkerRetorno) {
				map.removeLayer(searchMarkerRetorno);
			}
			// Crear un nuevo marcador en la ubicación geocodificada
			searchMarkerRetorno = L.marker(center)
				.addTo(map)
				.bindPopup(e.geocode.name)
				.openPopup();
			// Centrar el mapa en el marcador
			mapaRetorno.setView(center, 15); // Ajusta el zoom según tus necesidades
		})
		.addTo(mapaRetorno);




		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom: 19}).addTo(mapaIda);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom: 19}).addTo(mapaRetorno);

		// Añadir las paradas a ambos mapas
		paradasIda.forEach(function(parada) {
			
			L.marker([JSON.parse(parada['coordenadas'])[0],JSON.parse(parada['coordenadas'])[1]]).addTo(mapaIda)
			.bindPopup(parada.nombre)
        	.bindTooltip(`<strong>${parada.orden}</strong>`, { permanent: true, direction: 'right', offset: [0, 0] });
		});
		paradasRetorno.forEach(function(parada) {
			L.marker([JSON.parse(parada['coordenadas'])[0],JSON.parse(parada['coordenadas'])[1]]).addTo(mapaRetorno)
			.bindPopup(parada.nombre)
        	.bindTooltip(`<strong>${parada.orden}</strong>`, { permanent: true, direction: 'right', offset: [0, 0] });
		});

		// Crear featureGroups para la edición
		var featureGroupIda = L.featureGroup().addTo(mapaIda);
		var featureGroupRetorno = L.featureGroup().addTo(mapaRetorno);

		// Dibujar las polilíneas iniciales si existen y no son nulas
		var lineaIda = null;
		var lineaRetorno = null;

		

		if (coordenadasIdas && coordenadasIdas.length > 0) {
			
			lineaIda = L.polyline(coordenadasIdas, { color: 'blue' }).addTo(featureGroupIda);
			agregarFlechas(lineaIda, mapaIda);
		}
		if (coordenadasRetorno && coordenadasRetorno.length > 0) {
			lineaRetorno = L.polyline(coordenadasRetorno, { color: 'red' }).addTo(featureGroupRetorno);
			agregarFlechas(lineaRetorno, mapaRetorno);
		}

		// Añadir la funcionalidad de dibujo y edición para la ruta de ida
		var drawControlIda = new L.Control.Draw({
			edit: {
				featureGroup: featureGroupIda,
				remove: false
			},
			draw: {
				polyline: true,
				polygon: false,
				circle: false,
				marker: false,
				rectangle: false,
                circlemarker: false
			}
		});
		mapaIda.addControl(drawControlIda);

		// Añadir la funcionalidad de dibujo y edición para la ruta de retorno
		var drawControlRetorno = new L.Control.Draw({
			edit: {
				featureGroup: featureGroupRetorno,
				remove: false
			},
			draw: {
				polyline: true,
				polygon: false,
				circle: false,
				marker: false,
				rectangle: false,
                circlemarker: false
			}
		});
		mapaRetorno.addControl(drawControlRetorno);

		// Función para enviar las coordenadas con AJAX
		function enviarCoordenadas() {
			var data = {
				_token: '{{ csrf_token() }}',
				coordenadasIda: nuevasCoordenadasIda,
				coordenadasRetorno: nuevasCoordenadasRetorno
			};

			$.ajax({
				url: '/rutas/' + {{ $ruta->id }} + '/actualizar-coordenadas',
				type: 'POST',
				data: data,
				success: function(response) {
					new Noty({
						text: response.message,
						type: "alert"
					}).show();
				},
				error: function(xhr, status, error) {
					$.alert('Error al guardar las coordenadas: ' + xhr.responseText);
				}
			});
		}

		// Función para agregar flechas a una polilínea
		function agregarFlechas(polyline, map) {
			L.polylineDecorator(polyline, {
				patterns: [
					{
						offset: 25,
						repeat: 50,
						symbol: L.Symbol.arrowHead({
							pixelSize: 10,
							pathOptions: {
								fillOpacity: 1,
								weight: 0
							}
						})
					}
				]
			}).addTo(map);
		}

		// Detectar cambios en la línea de ida y enviar automáticamente
		mapaIda.on(L.Draw.Event.EDITED, function (e) {
			e.layers.eachLayer(function (layer) {
				nuevasCoordenadasIda = layer.getLatLngs().map(function(latlng) {
					return [latlng.lat, latlng.lng];
				});
				agregarFlechas(layer, mapaIda);
				enviarCoordenadas();
			});
		});

		// Detectar cambios en la línea de retorno y enviar automáticamente
		mapaRetorno.on(L.Draw.Event.EDITED, function (e) {
			e.layers.eachLayer(function (layer) {
				nuevasCoordenadasRetorno = layer.getLatLngs().map(function(latlng) {
					return [latlng.lat, latlng.lng];
				});
				agregarFlechas(layer, mapaRetorno);
				enviarCoordenadas();
			});
		});

		// Detectar creación de nuevas líneas y enviar automáticamente
		mapaIda.on(L.Draw.Event.CREATED, function (e) {
			var layer = e.layer;
			nuevasCoordenadasIda = layer.getLatLngs().map(function(latlng) {
				return [latlng.lat, latlng.lng];
			});
			featureGroupIda.addLayer(layer);
			agregarFlechas(layer, mapaIda);
			enviarCoordenadas();
		});

		mapaRetorno.on(L.Draw.Event.CREATED, function (e) {
			var layer = e.layer;
			nuevasCoordenadasRetorno = layer.getLatLngs().map(function(latlng) {
				return [latlng.lat, latlng.lng];
			});
			featureGroupRetorno.addLayer(layer);
			agregarFlechas(layer, mapaRetorno);
			enviarCoordenadas();
		});



	// borrar es para demo de actualizar la ubicacion de vehiculo
	// Función para enviar la ubicación del marcador
	function enviarUbicacion(lat, lng) {
		
		$.ajax({
            url: '{{ route("vehiculo.updateLocation", 19) }}', // Ruta que procesará la actualización
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                lat:lat,
                lng:lng
            },
            success: function(response) {

				console.log(response.message)
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

	}

	// Detectar click en el mapa de ida y colocar un marcador
	// mapaIda.on('click', function(e) {
	// 	var latLng = e.latlng;
	// 	L.marker([latLng.lat, latLng.lng]).addTo(mapaIda)
	// 		.bindPopup('Ubicación seleccionada: ' + latLng.lat + ', ' + latLng.lng).openPopup();

	// 	enviarUbicacion(latLng.lat, latLng.lng);
	// });

	// Detectar click en el mapa de retorno y colocar un marcador
	// mapaRetorno.on('click', function(e) {
	// 	var latLng = e.latlng;
	// 	L.marker([latLng.lat, latLng.lng]).addTo(mapaRetorno)
	// 		.bindPopup('Ubicación seleccionada: ' + latLng.lat + ', ' + latLng.lng).openPopup();

	// 	enviarUbicacion(latLng.lat, latLng.lng);
	// });



	</script>

@endpush