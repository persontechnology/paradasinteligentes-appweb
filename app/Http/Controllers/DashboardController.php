<?php

namespace App\Http\Controllers;

use App\Console\Commands\ObtenerYActualizarVehiculosApiRestEcuatrack;
use App\Events\ActualizarPosicionActualVehiculo;
use App\Events\VehiculoPosicionActualizada;
use App\Models\Configuracion;
use App\Models\Parada;
use App\Models\PosicionVehiculo;
use App\Models\RutaVehiculo;
use App\Models\SubRuta;
use App\Models\TipoRuta;
use App\Models\Vehiculo;
use Carbon\Carbon;
use Geotools\Coordinate\Coordinate;
use Geotools\Distance\Distance;
use Geotools\Geotools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{

    
    public function index2()
    {

       


        $configuracion = Configuracion::first();

        if (!$configuracion) {
            error_log('La configuración no está disponible.');
            return;
        }

        $url_web_gps = $configuracion->url_web_gps;
        $token = $configuracion->token;
        error_log($configuracion);
        try {
            error_log('Obteniendo datos de los dispositivos...');
            $responseApi = Http::get($url_web_gps . 'api/get_devices', [
                'user_api_hash' => $token,
                'lang' => 'es'
            ]);


            if ($responseApi->failed()) {
                error_log('Error al obtener datos de la API.');
                return;
            }

            $data = collect($responseApi->json('0.items', []));

            


        return $data;

            error_log('Datos de vehículos actualizados con éxito.');
        } catch (\Throwable $e) {
            error_log('Excepción al obtener o procesar los datos: ' . $e->getMessage());
        }
    }
    
    public function index()
    {
      
        
        $vehiculo=Vehiculo::find(19);

        // return $vehiculo->rutasActivasHoy();

        $data = array(
            'vehiculo'=>$vehiculo
        );
        return view('dashboard',$data);
    }

    public function updateLocation(Request $request, $id)
    {
        try {
            $vehiculo = Vehiculo::find($id);
            $vehiculo->ubicacion_actual = json_encode([$request->lat, $request->lng]);
            $vehiculo->save();

            // crear posicion actual de vehiculo tambien

            $actualizador=new ObtenerYActualizarVehiculosApiRestEcuatrack();
            $actualizador->actualizarUbicacionVehiculo($vehiculo);

            
            // event( new ActualizarPosicionActualVehiculo($vehiculo));

            return response()->json(['message' => 'Ubicación actualizada con éxito']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
    }
    



    
}
