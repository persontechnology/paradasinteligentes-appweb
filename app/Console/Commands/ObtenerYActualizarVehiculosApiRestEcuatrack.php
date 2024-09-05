<?php

namespace App\Console\Commands;

use App\Events\ActualizarPosicionActualVehiculo;
use App\Events\ActualizarRecorridoActualVehiculo;
use App\Events\VehiculoPosicionActualizada;
use App\Models\Configuracion;
use App\Models\PosicionVehiculo;
use App\Models\Vehiculo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ObtenerYActualizarVehiculosApiRestEcuatrack extends Command
{
    private $umbralMaximo = 0.0001; // Umbral máximo en grados de latitud/longitud (~11 metros)

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene y actualiza la ubicación de los vehículos desde la API de Ecuatrack';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        $configuracion = Configuracion::first();

        if (!$configuracion) {
            $this->error('La configuración no está disponible.');
            return;
        }

        $url_web_gps = $configuracion->url_web_gps;
        $token = $configuracion->token;
        $this->info($configuracion);
        try {
            $this->info('Obteniendo datos de los dispositivos...');
            $responseApi = Http::get($url_web_gps . 'api/get_devices', [
                'user_api_hash' => $token,
                'lang' => 'es'
            ]);


            if ($responseApi->failed()) {
                $this->error('Error al obtener datos de la API.');
                return;
            }

            $data = collect($responseApi->json('0.items', []));

            $result = $data->map(function ($item) {



                $codigo = $item['device_data']['traccar']['uniqueId'] ?? null;
                $name = $item['device_data']['traccar']['name'] ?? null;
                $latitud = $item['device_data']['traccar']['lastValidLatitude'] ?? null;
                $longitud = $item['device_data']['traccar']['lastValidLongitude'] ?? null;
                $velocidad = $item['device_data']['traccar']['speed'] ?? null;



                if (!$codigo) {
                    $this->info($velocidad);
                    return null;
                }

                $valores = [
                    'codigo' => $codigo,
                    'name' => $name,
                    'latitud' => $latitud,
                    'longitud' => $longitud,
                    'velocidad' => $velocidad
                ];

                return $valores;
            })->filter();

            

            foreach ($result as $vehiculoData) {

                
                

                $vehiculo = Vehiculo::updateOrCreate(
                    ['codigo' => $vehiculoData['codigo']],
                    [
                        'placa' => $vehiculoData['name'],
                        'ubicacion_actual' => json_encode([
                            $vehiculoData['latitud'],
                            $vehiculoData['longitud']
                        ]),
                        'velocidad'=>$vehiculoData['velocidad']
                    ]
                );

               $posicionVehiculo= $this->actualizarUbicacionVehiculo($vehiculo);
            }
            

            $this->info('Datos de vehículos actualizados con éxito.');
        } catch (\Throwable $e) {
            $this->error('Excepción al obtener o procesar los datos: ' . $e->getMessage());
        }
    }


    public function actualizarUbicacionVehiculo($vehiculo)
    {
        
        $rutasActivasHoy = $vehiculo->rutasActivasHoy();

        if($rutasActivasHoy->count()>0){
            $ubicacionActual = json_decode($vehiculo->ubicacion_actual);
            $rutaMasCercana = null;
            $distanciaMinima = PHP_INT_MAX;

            foreach ($rutasActivasHoy as $rutaActiva) {
                
                $coordenadasIda = json_decode($rutaActiva['coordenadas_ida'], true);
                $coordenadasRetorno = json_decode($rutaActiva['coordenadas_retorno'], true);

                $distanciaIda = $this->calcularDistanciaMinima($ubicacionActual, $coordenadasIda);
                $distanciaRetorno = $this->calcularDistanciaMinima($ubicacionActual, $coordenadasRetorno);

                if ($distanciaIda !== null && $distanciaIda < $distanciaMinima && $distanciaIda <= $this->umbralMaximo) {
                    $distanciaMinima = $distanciaIda;
                    $rutaMasCercana = [
                        'ruta' => $rutaActiva['ruta']->nombre,
                        'direccion' => 'IDA',
                        'distancia' => $distanciaIda,
                        'tipoRutaId' => $rutaActiva['ruta']->tipoRutaIda->id,
                    ];
                }

                if ($distanciaRetorno !== null && $distanciaRetorno < $distanciaMinima && $distanciaRetorno <= $this->umbralMaximo) {
                    $distanciaMinima = $distanciaRetorno;
                    $rutaMasCercana = [
                        'ruta' => $rutaActiva['ruta']->nombre,
                        'direccion' => 'RETORNO',
                        'distancia' => $distanciaRetorno,
                        'tipoRutaId' => $rutaActiva['ruta']->tipoRutaRetorno->id,
                    ];
                }
            }



            if ($rutaMasCercana) {
                
                $detalle="La ruta más cercana es: " . $rutaMasCercana['ruta'] . " de tipo " . $rutaMasCercana['direccion'] . " con una distancia de " . $rutaMasCercana['distancia'] . " metros.";
                
                return $this->crearPosicionVehiculo($vehiculo,'SI',$rutaMasCercana['tipoRutaId'],$detalle,$rutaMasCercana['direccion']);
            } else {
                
                $detalle= "El vehículo está fuera de las rutas activas.";

                return $this->crearPosicionVehiculo($vehiculo,'NO',null,$detalle,'N/A');
            }
        }else{
            $this->info('NO TIENE RUTAS ACTIVAS HOY');
        }

        
    }

    private function crearPosicionVehiculo($vehiculo,$esta_ruta,$tipo_ruta_id,$detalle,$direccion) {

        
        // Obtener el último registro de posición del vehículo
        $ultimaPosicion = PosicionVehiculo::where('vehiculo_id', $vehiculo->id)
            ->orderBy('created_at', 'desc')
            ->first();

            
        // Verificar si las coordenadas del último registro son iguales a las coordenadas actuales
        if ($ultimaPosicion && $ultimaPosicion->coordenadas == $vehiculo->ubicacion_actual) {
            // Si las coordenadas son iguales, no crear un nuevo registro
            return;
        }

        
        

        $pv= PosicionVehiculo::create([
            'coordenadas'=>$vehiculo->ubicacion_actual,
            'esta_ruta'=>$esta_ruta,
            'tipo_ruta_id'=>$tipo_ruta_id,
            'detalle'=>$detalle,
            'vehiculo_id'=>$vehiculo->id,
            'velocidad'=>$vehiculo->velocidad,
            'direccion'=>$direccion
        ]);
        
        event( new ActualizarPosicionActualVehiculo($pv->vehiculo));
        event(new ActualizarRecorridoActualVehiculo($pv));


        return $pv;
        
    }

    private function calcularDistanciaMinima($point, $polyline)
    {
        if (!$polyline) return null;
        
        $minDistancia = PHP_INT_MAX;

        foreach ($polyline as $i => $coord) {
            if ($i == 0) continue;

            $segmentStart = $polyline[$i - 1];
            $segmentEnd = $coord;

            $distancia = $this->pointToSegmentDistance($point, $segmentStart, $segmentEnd);
            if ($distancia < $minDistancia) {
                $minDistancia = $distancia;
            }
        }

        return $minDistancia === PHP_INT_MAX ? null : $minDistancia;
    }

    private function pointToSegmentDistance($point, $segmentStart, $segmentEnd) {
        $x0 = $point[0];
        $y0 = $point[1];
        $x1 = $segmentStart[0];
        $y1 = $segmentStart[1];
        $x2 = $segmentEnd[0];
        $y2 = $segmentEnd[1];

        $A = $x0 - $x1;
        $B = $y0 - $y1;
        $C = $x2 - $x1;
        $D = $y2 - $y1;

        $dot = $A * $C + $B * $D;
        $len_sq = $C * $C + $D * $D;
        $param = $len_sq != 0 ? $dot / $len_sq : -1;

        $xx = $x1 + $param * $C;
        $yy = $y1 + $param * $D;

        if ($param < 0) {
            $xx = $x1;
            $yy = $y1;
        } else if ($param > 1) {
            $xx = $x2;
            $yy = $y2;
        }

        $dx = $x0 - $xx;
        $dy = $y0 - $yy;
        return sqrt($dx * $dx + $dy * $dy);
    }


    

}
