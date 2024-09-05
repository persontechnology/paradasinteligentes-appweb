<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $fillable=[
        'placa',
        'codigo',
        'marca',
        'modelo',
        'nombre_cooperativa',
        'foto',
        'descripcion',
        'estado',
        'coductor_id',
        'ayudante_id',
        'ubicacion_actual',
        'numero_linea',
        'velocidad'
    ];

    protected $casts = [
        'ubicacion_actual' => 'array',
        'velocidad'=>'decimal:2'
    ];

    // Accesor para el nombre del conductor
    public function getNombreConductorAttribute()
    {
        return $this->conductor ? $this->conductor->name : 'N/A';
    }

    // Accesor para el nombre del ayudante
    public function getNombreAyudanteAttribute()
    {
        return $this->ayudante ? $this->ayudante->name : 'N/A';
    }
    
    // Genera un enlace a Google Maps usando las coordenadas de ubicacion_actual.
    public function enlaceGoogleMaps()
    {
        // Asegurarse de que ubicacion_actual es un array
        $ubicacion = is_array($this->ubicacion_actual) ? $this->ubicacion_actual : json_decode($this->ubicacion_actual, true);
        
        if (isset($ubicacion[0]) && isset($ubicacion[1])) {
            $lat = $ubicacion[0];
            $lng = $ubicacion[0];

            // Crear el enlace a Google Maps
            return "https://www.google.com/maps?q={$lat},{$lng}";
        }

        // Retornar null o un mensaje si no hay ubicación válida
        return null;
    }

    public function rutas()
    {
        return $this->belongsToMany(Ruta::class, 'ruta_vehiculos')
        ->withPivot('dias_activos')
        ->where('rutas.estado', 'ACTIVO');
    }
    

    // Relación uno a muchos (inversa) con User para conductor
    public function conductor()
    {
        return $this->belongsTo(User::class, 'coductor_id');
    }

    // Relación uno a muchos (innumero_lineaversa) con User para ayudante
    public function ayudante()
    {
        return $this->belongsTo(User::class, 'ayudante_id');
    }


    public function rutasActivasHoy()
    {
        // Obtiene el día actual en español, por ejemplo, "lunes"
        $diaActual = Carbon::now()->locale('es')->dayName;

        return $this->rutas()->where(function ($query) use ($diaActual) {
            $query->whereJsonContains('ruta_vehiculos.dias_activos', $diaActual);
        })->with(['tipoRutaIda', 'tipoRutaRetorno'])->get()->map(function($ruta) {
            return [
                'ruta' => $ruta,
                'coordenadas_ida' => $ruta->tipoRutaIda ? $ruta->tipoRutaIda->coordenadas : null,
                'coordenadas_retorno' => $ruta->tipoRutaRetorno ? $ruta->tipoRutaRetorno->coordenadas : null,
            ];
        });
    }


}
