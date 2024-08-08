<?php

namespace App\Http\Controllers;

use App\DataTables\VehiculoDataTable;
use App\Models\Ruta;
use App\Models\User;
use App\Models\Vehiculo;
use App\Models\VehiculoRuta;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(VehiculoDataTable $dataTable)
    {
        return $dataTable->render('vehiculos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = array(
            'usuarios'=>User::get(),
            'rutas'=>Ruta::get()
        );
        return view('vehiculos.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'placa' => 'required|string|max:255|unique:vehiculos,placa',
            'codigo' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'nombre_cooperativa' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:ACTIVO,INACTIVO',
            'conductor' => 'required|integer|exists:users,id', // Asume que conductor es un usuario
            'ayudante' => 'nullable|integer|exists:users,id',
            'rutas' => 'required|array',
            'rutas.*' => 'integer|exists:rutas,id', // Verifica que las rutas existen
        ]);
        
        $request['coductor_id']=$request->conductor;
        $request['ayudante_id']=$request->ayudante;
        $vehiculo = Vehiculo::create($request->all());
        foreach ($request->rutas as $ruta_id) {
            VehiculoRuta::create([
                'vehiculo_id' => $vehiculo->id,
                'ruta_id' => $ruta_id,
            ]);
        }

        return redirect()->route('vehiculos.horario',$vehiculo)->with('success', 'Vehículo y rutas guardados con éxito.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Vehiculo $vehiculo)
    {
        
    }

    

    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehiculo $vehiculo)
    {
        $data = array(
            'vehiculo' => $vehiculo,
            'usuarios' => User::get(),
            'rutas' => Ruta::get(),
        );
        return view('vehiculos.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehiculo $vehiculo)
    {
        $request->validate([
            'placa' => 'required|string|max:255|unique:vehiculos,placa,' . $vehiculo->id,
            'codigo' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'nombre_cooperativa' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:ACTIVO,INACTIVO',
            'conductor' => 'required|integer|exists:users,id',
            'ayudante' => 'nullable|integer|exists:users,id',
            'rutas' => 'required|array',
            'rutas.*' => 'integer|exists:rutas,id',
        ]);
    
        $request['coductor_id']=$request->conductor;
        $request['ayudante_id']=$request->ayudante;
        $vehiculo->update($request->all());
        
        // Actualizar rutas asociadas
        $vehiculo->rutas()->sync($request->rutas);
    
        return redirect()->route('vehiculos.horario',$vehiculo)->with('success', 'Vehículo actualizado con éxito.');
    }

    public function horario(Vehiculo $vehiculo)
    {
        // Cargar las rutas con la información del pivote (VehiculoRuta)
        $vehiculo->load('rutas');
        return view('vehiculos.horario', compact('vehiculo'));
    }

    public function horarioActualizar(Request $request, $vehiculoId)
    {
        $vehiculo=Vehiculo::findOrFail($vehiculoId);
        // Validar la entrada
        $request->validate([
            'dias_activos.*' => 'array',
            'dias_activos.*.*' => 'in:lunes,martes,miercoles,jueves,viernes,sabado,domingo'
        ]);
        

        // Iterar sobre las rutas y actualizar los días activos
        foreach ($vehiculo->rutas as $ruta) {
            $diasActivos = $request->input("dias_activos.{$ruta->id}", []);
            $vehiculo->rutas()->updateExistingPivot($ruta->id, ['dias_activos' => json_encode($diasActivos)]);
        }

        return redirect()->route('vehiculos.horario', $vehiculo)->with('success', 'Días de la semana actualizados correctamente.');
        
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehiculo $vehiculo)
    {
        try {
            $vehiculo->delete();
            return back()->with('success','Vehículo eliminado.! ');
        } catch (\Throwable $th) {
            return back()->with('error','No se puede eliminar vehículo.! '.$th->getMessage());
        }
    }
}
