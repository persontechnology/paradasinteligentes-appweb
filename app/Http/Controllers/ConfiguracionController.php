<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $configuracion = Configuracion::first();
        
        return view('configuracion.index', compact('configuracion'));
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
        $request->validate([
            'frecuencia' => 'required|string',
            'url_web_gps' => 'required|url',
            'token' => 'required|string'
        ]);

        // Actualiza o crea la configuración
        Configuracion::updateOrCreate(
            ['id' => 1], // Asegura que solo hay un registro
            [
                'frecuencia' => $request->frecuencia,
                'url_web_gps'=>$request->url_web_gps,
                'token'=>$request->token
            ]
        );

        return redirect()->route('configuracion.index')->with('success', 'Frecuencia actualizada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Configuracion $configuracion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Configuracion $configuracion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Configuracion $configuracion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Configuracion $configuracion)
    {
        //
    }
}
