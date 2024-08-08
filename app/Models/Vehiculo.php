<?php

namespace App\Models;

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
        'ubicacion_actual'
    ];

    protected $casts = [
        'ubicacion_actual' => 'array', // Castea ubicacion_actual como array para JSON
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
        // Verifica que ubicacion_actual es un array con latitud y longitud
        if (is_array($this->ubicacion_actual) && count($this->ubicacion_actual) == 2) {
            $latitud = $this->ubicacion_actual[0];
            $longitud = $this->ubicacion_actual[1];

            // Construir el enlace de Google Maps
            return "https://www.google.com/maps?q={$latitud},{$longitud}";
        }

        return null; // Maneja el caso donde las coordenadas no son válidas
    }

    // Relación muchos a muchos con Ruta
    public function rutas()
    {
        return $this->belongsToMany(Ruta::class, 'vehiculo_rutas')->withPivot('dias_activos');
    }

    // Relación uno a muchos (inversa) con User para conductor
    public function conductor()
    {
        return $this->belongsTo(User::class, 'coductor_id');
    }

    // Relación uno a muchos (inversa) con User para ayudante
    public function ayudante()
    {
        return $this->belongsTo(User::class, 'ayudante_id');
    }

    public function vehiculoRutas()
    {
        return $this->hasMany(VehiculoRuta::class);
    }


}
