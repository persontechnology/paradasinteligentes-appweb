<?php

namespace App\Http\Controllers;

use App\DataTables\RutaDataTable;
use App\Models\Parada;
use App\Models\Ruta;
use App\Models\RutaParada;
use App\Models\SubRuta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Builder\Param;

class RutaController extends Controller
{
    
    public function index(RutaDataTable $rutaDataTable)
    {
        return $rutaDataTable->render('rutas.index');
    }

    public function create()
    {
        $data = array(
            'paradas'=>Parada::get(),
            'paradas_activas'=>Parada::contarActivos(),
            'paradas_inactivas'=>Parada::contarInactivos(),
        );
        
        return view('rutas.create',$data);
    }

   
    public function store(Request $request)
    {
        // Validar los datos entrantes
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:ACTIVO,INACTIVO',
            'subrutas' => 'required|json'
        ]);
        
        // Crear la ruta
        $ruta = Ruta::create($request->only(['nombre', 'descripcion', 'estado']));

        // Decodificar subrutas del JSON
        $subrutasData = json_decode($request->input('subrutas'), true);

        // Almacenar cada subruta
        foreach ($subrutasData as $subruta) {
            
            
            SubRuta::create([
                'ruta_id' => $ruta->id,
                'parada_inicio_id' => $subruta['parada_inicio_id'],
                'parada_final_id' => $subruta['parada_final_id'],
                'tiempo_recorrido' => $subruta['tiempo_recorrido'],
                'coordenadas' => json_encode($subruta['coordenadas'])
            ]);
        }

        return redirect()->route('rutas.index')->with('success', 'Ruta creada exitosamente.');
    }
    public function show(Ruta $ruta)
    {
        return $ruta;
    }

  
    public function edit(Ruta $ruta)
    {
        
       $subrutas = $ruta->subRutas()->with(['paradaInicio', 'paradaFinal'])->get()->map(function($subruta) {
            $subruta->coordenadas = json_decode($subruta->coordenadas, true);
            return $subruta;
        });

        $data = array(
            'ruta'=>$ruta,
            'paradas'=>Parada::all(),
            'subrutas'=>$subrutas,
            'paradas_activas'=>Parada::contarActivos(),
            'paradas_inactivas'=>Parada::contarInactivos(),
        );
        return view('rutas.edit',$data);   
    }

    public function update(Request $request, Ruta $ruta)
    {
        // Validar los datos entrantes
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:ACTIVO,INACTIVO',
            'subrutas' => 'required|json'
        ]);

        
        try {
            DB::beginTransaction();
            // Actualizar la ruta
            $ruta->update($request->only(['nombre', 'descripcion', 'estado']));

            // Eliminar las subrutas existentes
            $ruta->subRutas()->delete();

            // Decodificar subrutas del JSON
             $subrutasData = json_decode($request->input('subrutas'), true);

            // Almacenar cada subruta
            foreach ($subrutasData as $subruta) {
                SubRuta::create([
                    'ruta_id' => $ruta->id,
                    'parada_inicio_id' => $subruta['parada_inicio_id'],
                    'parada_final_id' => $subruta['parada_final_id'],
                    'tiempo_recorrido' => $subruta['tiempo_recorrido'],
                    'coordenadas' => json_encode($subruta['coordenadas'])
                ]);
            }
            DB::commit();
            return redirect()->route('rutas.index')->with('success', 'Ruta actualizada exitosamente.');
            
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('rutas.index')->with('error', 'Ruta no actualizada.'.$th->getMessage());
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ruta $ruta)
    {
        try {
            $ruta->delete();
            return redirect()->route('rutas.index')->with('success','Ruta eliminado');
        } catch (\Throwable $th) {
            return redirect()->route('rutas.index')->with('success','Ruta no eliminado.'.$th->getMessage());
        }
    }

    
    public function eliminarSubRuta($id)
    {
        $subruta = Subruta::find($id);

        if (!$subruta) {
            return response()->json(['message' => 'Subruta no encontrada.'], 404);
        }

        $subruta->delete();

        return response()->json(['message' => 'Subruta eliminada correctamente.'], 200);
    }

   
}
