<?php

namespace App\Http\Controllers;

use App\DataTables\ParadaDatableDataTable;
use App\Models\Parada;
use App\Models\Ruta;
use Illuminate\Http\Request;

class ParadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ParadaDatableDataTable $dataTable)
    {
        
        $data = array(
            'paradas'=>Parada::all(),
            'paradas_activas'=>Parada::contarActivos(),
            'paradas_inactivas'=>Parada::contarInactivos(),

        );
        return $dataTable->render('paradas.index',$data);
    }
    
}
