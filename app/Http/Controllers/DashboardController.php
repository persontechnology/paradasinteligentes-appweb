<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index() {
        // return $this->obtenerVehiculosUbicacion();
        return view('dashboard');
    }

    public function obtenerVehiculosUbicacion() {
        $url_web_gps = "http://www.ecuatracker.com/";
        $token = '$2y$10$SFqWZ6.d1YiuHdrHNn0ES.gplwfWYvchltthxCCKn8llhBzdD3X62';
    
        try {
            // Llama a la API para obtener los datos de los dispositivos
            $responseApi = Http::get($url_web_gps . 'api/get_devices', [
                'user_api_hash' => $token,
                'lang' => 'es'
            ]);
    
            if ($responseApi->failed()) {
                error_log('Error al obtener datos de la API.');
                return collect();
            }
    
            // Convierte la respuesta de la API en una colección y extrae los datos necesarios
            $data = collect($responseApi->json('0.items', []));
    
            // Mapea y filtra los datos para extraer solo lo necesario y válido
            $result = $data->map(function ($item) {
                $codigo = $item['device_data']['traccar']['uniqueId'] ?? null;
                $name = $item['device_data']['traccar']['name'] ?? null;
                $latitud = $item['device_data']['traccar']['lastValidLatitude'] ?? null;
                $longitud = $item['device_data']['traccar']['lastValidLongitude'] ?? null;
    
                // Retorna null si alguno de los campos esenciales es nulo
                if (!$codigo || !$latitud || !$longitud) {
                    return null;
                }
    
                // Retorna el arreglo si todos los campos son válidos
                return [
                    'codigo' => $codigo,
                    'name' => $name,
                    'latitud' => $latitud,
                    'longitud' => $longitud,
                    // Agrega más campos según lo necesites
                ];
            })->filter(); // Filtra los elementos nulos
    
            // Itera sobre los resultados filtrados y crea o actualiza los vehículos
            foreach ($result as $vehiculoData) {
                // Usa updateOrCreate para crear o actualizar registros
                Vehiculo::updateOrCreate(
                    ['codigo' => $vehiculoData['codigo']], // Condición para encontrar el vehículo existente
                    [
                        // Almacena las coordenadas directamente como un array, el modelo manejará la conversión a JSON
                        // 'placa' => $vehiculoData['name'],
                        'ubicacion_actual' => [$vehiculoData['latitud'], $vehiculoData['longitud']]
                    ]
                );
            }
    
            return $result;
    
        } catch (\Throwable $e) {
            error_log('Excepción al obtener o procesar los datos: ' . $e->getMessage());
            return collect();
        }
    }
}
