<?php

namespace App\Http\Controllers;

use App\Models\Parada;
use Illuminate\Http\Request;

class ParadaAdminController extends Controller
{
    public function index()
    {
        $paradas = Parada::all();
        return response()->json($paradas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'geocerca' => 'required'  // Asegurarse de que el formato JSON sea correcto
        ]);

        $parada = Parada::create($validated);
        return response()->json([
            'message'=>'Parada '.$parada->nombre.' ingresado.!',
            'id'=>$parada->id,
            'nombre'=>$parada->nombre

        ]);
    }

    public function show(Parada $parada)
    {
        return response()->json($parada);
    }

    public function update(Request $request, $id)
    {
        $parada = Parada::findOrFail($id);
        $validated = $request->validate([
            'nombre' => 'required|string',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'geocerca' => 'required'
        ]);

        $parada->update($validated);
        return response()->json([
            'message'=>'Parada '.$parada->nombre.' actualizado.!',
        ]);
    }

    public function destroy($id)
    {
        $parada = Parada::findOrFail($id);
        $parada->delete();
        return response()->json(null, 204);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $parada = Parada::findOrFail($id);
        $parada->estado = $request->input('estado');
        $parada->save();

        return response()->json([
            'message' => 'Estado de la parada '.$parada->nombre.' actualizado a '.$parada->estado,
            'color'=>$parada->estado=='ACTIVO'?'blue':'red'

        ]);
    }
}
