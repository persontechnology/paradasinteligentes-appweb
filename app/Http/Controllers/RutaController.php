<?php

namespace App\Http\Controllers;

use App\DataTables\RutaDataTable;
use App\Models\Parada;
use App\Models\Ruta;
use App\Models\TipoRuta;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class RutaController extends Controller
{

    public function index(RutaDataTable $rutaDataTable)
    {
        return $rutaDataTable->render('rutas.index');
    }

    public function create()
    {
        $data = array(
            'paradas' => Parada::all(),
            'vehiculos' => Vehiculo::all()
        );

        return view('rutas.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_ruta' => 'required|string|max:255|unique:rutas,nombre',
            'vehiculos' => 'nullable|array',
            'vehiculos.*' => 'exists:vehiculos,id',
            'ida_inicio' => 'required|date_format:H:i',
            'ida_finaliza' => 'required|date_format:H:i|after:ida_inicio',
            'ida_tiempo_total' => 'required|string|max:50',
            'retorno_inicio' => 'required|date_format:H:i',
            'retorno_finaliza' => 'required|date_format:H:i|after:retorno_inicio',
            'retorno_tiempo_total' => 'required|string|max:50',
            'estado' => 'required|in:ACTIVO,INACTIVO',
            'paradas_ida' => 'required|array',
            'paradas_ida.*' => 'exists:paradas,id',
            'paradas_retorno' => 'required|array',
            'paradas_retorno.*' => 'exists:paradas,id',
            'detalle_recorrido_ida' => 'required|string',
            'detalle_recorrido_retorno' => 'required|string',
            'distancia_total' => 'required|string|max:255',
            'tiempo_total_ruta' => 'required|string|max:255',
            'dias_activos' => 'required|array|min:1', // Asegura que se seleccionen al menos un día
            'dias_activos.*' => 'in:lunes,martes,miércoles,jueves,viernes,sábado,domingo',
        ]);

        // Crear la ruta
        $ruta = Ruta::create([
            'nombre' => $request->nombre_ruta,
            'estado' => $request->estado,
            'distancia_total' => $request->distancia_total,
            'tiempo_total_ruta' => $request->tiempo_total_ruta,
        ]);

        // Asignar vehículos a la ruta con días activos
        // $ruta->vehiculos()->sync($request->vehiculos);
        if ($request->vehiculos) {
            $vehiculosData = [];
            foreach ($request->vehiculos as $vehiculoId) {
                $vehiculosData[$vehiculoId] = [
                    'dias_activos' => json_encode($request->dias_activos,JSON_UNESCAPED_UNICODE),
                ];
            }
            $ruta->vehiculos()->sync($vehiculosData);
        }

        // Crear TipoRuta para la ida
        $tipoRutaIda = TipoRuta::create([
            'tipo' => 'IDA',
            'ruta_id' => $ruta->id,
            'inicio' => $request->ida_inicio,
            'finaliza' => $request->ida_finaliza,
            'tiempo_total' => $request->ida_tiempo_total,
            'detalle_recorrido' => $request->detalle_recorrido_ida

        ]);

        // Asignar paradas a la TipoRuta (ida)
        $tipoRutaIda->paradas()->sync($this->prepareRecorridoData($request->paradas_ida));
        
        // Crear TipoRuta para el retorno
        $tipoRutaRetorno = TipoRuta::create([
            'tipo' => 'RETORNO',
            'ruta_id' => $ruta->id,
            'inicio' => $request->retorno_inicio,
            'finaliza' => $request->retorno_finaliza,
            'tiempo_total' => $request->retorno_tiempo_total,
            'detalle_recorrido' => $request->detalle_recorrido_retorno

        ]);

        // Asignar paradas a la TipoRuta (retorno)
        $tipoRutaRetorno->paradas()->sync($this->prepareRecorridoData($request->paradas_retorno));

        return redirect()->route('rutas.show',$ruta->id)->with('success', 'Ruta creada con éxito.');
    }



    private function prepareRecorridoData($paradas)
    {
        $data = [];
        foreach ($paradas as $index => $paradaId) {
            $data[$paradaId] = ['orden' => $index + 1];
        }
        return $data;
    }



    public function show(Ruta $ruta)
    {
        $paradasIda = array();
        foreach ($ruta->tipoRutaIda->recorridos as $ri) {
            $ri->parada['orden']=$ri->orden;
            array_push($paradasIda, $ri->parada);
        }

        

        $paradasRetorno = array();
        foreach ($ruta->tipoRutaRetorno->recorridos as $re) {
            $re->parada['orden']=$re->orden;
            array_push($paradasRetorno, $re->parada);
        }
        
        // Decodificar coordenadas JSON si existen
         $coordenadasIdas = $ruta->tipoRutaIda->coordenadas ? json_decode($ruta->tipoRutaIda->coordenadas) : [];
         $coordenadasRetorno = $ruta->tipoRutaRetorno->coordenadas ? json_decode($ruta->tipoRutaRetorno->coordenadas) : [];

        $data = array(
            'ruta' => $ruta,
            'paradasIda' => $paradasIda,
            'paradasRetorno' => $paradasRetorno,
            'coordenadasIdas' => $coordenadasIdas,
            'coordenadasRetorno' => $coordenadasRetorno,
        );

        return view('rutas.show', $data);
    }




    public function edit($id)
    {
        $ruta = Ruta::findOrFail($id);
        $vehiculos = Vehiculo::all();
        $paradas = Parada::all();
        
        // Filtrando paradas para ida y retorno
        $tipoRutaIda = $ruta->tipoRutaIda;
        $tipoRutaRetorno = $ruta->tipoRutaRetorno;

        $paradasIda = $tipoRutaIda ? $tipoRutaIda->paradas()->orderBy('pivot_orden')->get() : collect();
        $paradasRetorno = $tipoRutaRetorno ? $tipoRutaRetorno->paradas()->orderBy('pivot_orden')->get() : collect();

        return view('rutas.edit', compact('ruta', 'vehiculos', 'paradas', 'paradasIda', 'paradasRetorno', 'tipoRutaIda', 'tipoRutaRetorno'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_ruta' => 'required|string|max:255|unique:rutas,nombre,'.$id,
            'vehiculos' => 'nullable|array',
            'vehiculos.*' => 'exists:vehiculos,id',
            'ida_inicio' => 'required|date_format:H:i',
            'ida_finaliza' => 'required|date_format:H:i|after:ida_inicio',
            'ida_tiempo_total' => 'required|string|max:50',
            'retorno_inicio' => 'required|date_format:H:i',
            'retorno_finaliza' => 'required|date_format:H:i|after:retorno_inicio',
            'retorno_tiempo_total' => 'required|string|max:50',
            'estado' => 'required|in:ACTIVO,INACTIVO',
            'paradas_ida' => 'required|array',
            'paradas_ida.*' => 'exists:paradas,id',
            'paradas_retorno' => 'required|array',
            'paradas_retorno.*' => 'exists:paradas,id',
            'detalle_recorrido_ida' => 'required|string',
            'detalle_recorrido_retorno' => 'required|string',
            'distancia_total' => 'required|string|max:255',
            'tiempo_total_ruta' => 'required|string|max:255',
            'dias_activos' => 'required|array|min:1', // Asegura que se seleccionen al menos un día
            'dias_activos.*' => 'in:lunes,martes,miércoles,jueves,viernes,sábado,domingo', 
        ]);

        // Actualizar la ruta
        $ruta = Ruta::findOrFail($id);
        $ruta->update([
            'nombre' => $request->nombre_ruta,
            'estado' => $request->estado,
            'distancia_total' => $request->distancia_total,
            'tiempo_total_ruta' => $request->tiempo_total_ruta,
        ]);

        // Actualizar vehículos
       // Actualizar vehículos con días activos
        if ($request->vehiculos) {
            $vehiculosData = [];
            foreach ($request->vehiculos as $vehiculoId) {
                $vehiculosData[$vehiculoId] = [
                    'dias_activos' => json_encode($request->dias_activos,JSON_UNESCAPED_UNICODE),
                ];
            }
            $ruta->vehiculos()->sync($vehiculosData);
        }

        // Actualizar TipoRuta para la ida
        $tipoRutaIda = $ruta->tipoRutaIda;
        if ($tipoRutaIda) {
            $tipoRutaIda->update([
                'inicio' => $request->ida_inicio,
                'finaliza' => $request->ida_finaliza,
                'tiempo_total' => $request->ida_tiempo_total,
                'detalle_recorrido' => $request->detalle_recorrido_ida,
            ]);
            $tipoRutaIda->paradas()->sync($this->prepareRecorridoData($request->paradas_ida));
        }

        // Actualizar TipoRuta para el retorno
        $tipoRutaRetorno = $ruta->tipoRutaRetorno;
        if ($tipoRutaRetorno) {
            $tipoRutaRetorno->update([
                'inicio' => $request->retorno_inicio,
                'finaliza' => $request->retorno_finaliza,
                'tiempo_total' => $request->retorno_tiempo_total,
                'detalle_recorrido' => $request->detalle_recorrido_retorno,
            ]);
            $tipoRutaRetorno->paradas()->sync($this->prepareRecorridoData($request->paradas_retorno));
        }

        return redirect()->route('rutas.index')->with('success', 'Ruta actualizada con éxito.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ruta $ruta)
    {
        try {
            $ruta->delete();
            return redirect()->route('rutas.index')->with('success', 'Ruta eliminado');
        } catch (\Throwable $th) {
            return redirect()->route('rutas.index')->with('success', 'Ruta no eliminado.' . $th->getMessage());
        }
    }


    public function actualizarCoordenadas(Request $request, Ruta $ruta)
    {
        // Validar que se reciban arrays
        $request->validate([
            'coordenadasIda' => 'nullable|array',
            'coordenadasRetorno' => 'nullable|array',
        ]);
        
        // Guardar las coordenadas de ida si se proporcionaron
        if ($request->has('coordenadasIda')) {
            $ruta->tipoRutaIda->coordenadas = json_encode($request->coordenadasIda);
            $ruta->tipoRutaIda->save();
            
        }

        // Guardar las coordenadas de retorno si se proporcionaron
        if ($request->has('coordenadasRetorno')) {
            $ruta->tipoRutaRetorno->coordenadas = json_encode($request->coordenadasRetorno);
            $ruta->tipoRutaRetorno->save();
            
        }
        
        return response()->json(['message' => 'Coordenadas actualizadas correctamente']);
    }

}
