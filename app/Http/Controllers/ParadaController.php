<?php

namespace App\Http\Controllers;

use App\DataTables\ParadaDatableDataTable;
use App\Models\Parada;
use App\Models\Ruta;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ParadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array(
            'paradas'=>Parada::all()
        );
        return View('paradas.index',$data);
    }


    public function mapa(ParadaDatableDataTable $dataTable)
    {
        
        $data = array(
            'paradas'=>Parada::all(),
            'paradas_activas'=>Parada::contarActivos(),
            'paradas_inactivas'=>Parada::contarInactivos(),

        );
        return $dataTable->render('paradas.mapa',$data);
    }


    public function show(Parada $parada){
        
        // Formatear la fecha actual
        $fecha = \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');

        $data = array(
            'parada'=>$parada,
            'fecha'=>$fecha
        );

        return view('paradas.show',$data);
    }

    public function store(Request $request)
    {
        try {
            $parada = new Parada();
            $parada->nombre = $request->input('nombre');
            $parada->coordenadas = json_encode($request->input('coordenadas'));
            $parada->save();

            return response()->json(['message' => 'Parada creada exitosamente', 'parada' => $parada]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
    }

    public function actualizarCoordenadas(Request $request, $id)
    {
        try {
            $parada = Parada::findOrFail($id);
            $parada->nombre=$request->input('nombre');
            $parada->coordenadas = json_encode($request->input('coordenadas'));
            $parada->save();
            return response()->json(['message' => 'Coordenadas actualizadas exitosamente', 'parada' => $parada]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $parada = Parada::findOrFail($id);
        $parada->delete();

        return response()->json(['message' => 'Parada eliminada exitosamente']);
    }
    
}
