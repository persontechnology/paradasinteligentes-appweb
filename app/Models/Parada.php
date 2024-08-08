<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parada extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'latitud', 'longitud', 'geocerca'];

    protected $casts = [
        'geocerca' => 'array'
    ];

    // Otros métodos y propiedades del modelo

    
    //  Método para contar las paradas con estado 'ACTIVO'.
    public static function contarActivos()
    {
        // Cuenta el número de paradas donde el estado es 'ACTIVO'
        return self::where('estado', 'ACTIVO')->count();
    }

    
    // Método para contar las paradas con estado 'INACTIVO'.
    public static function contarInactivos()
    {
        // Cuenta el número de paradas donde el estado es 'INACTIVO'
        return self::where('estado', 'INACTIVO')->count();
    }
        
}
