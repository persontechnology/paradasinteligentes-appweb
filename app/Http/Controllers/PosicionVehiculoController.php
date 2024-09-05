<?php

namespace App\Http\Controllers;

use App\DataTables\PosicionVehiculoDataTable;
use App\Models\PosicionVehiculo;
use Illuminate\Http\Request;

class PosicionVehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PosicionVehiculoDataTable $dataTable)
    {
        return $dataTable->render('posicion-vehiculos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PosicionVehiculo $posicionVehiculo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PosicionVehiculo $posicionVehiculo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PosicionVehiculo $posicionVehiculo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($posicionVehiculoId)
    {
        try {
            $posicionVehiculo=PosicionVehiculo::findOrFail($posicionVehiculoId);
            $posicionVehiculo->delete();
            return redirect()->route('poisicion-vehiculos.index')->with('success','Posición de vehículo eliminado.');
        } catch (\Throwable $th) {
            return redirect()->route('poisicion-vehiculos.index')->with('error','Posición de vehículo no eliminado, '.$th->getMessage());
        }
        
    }
}
