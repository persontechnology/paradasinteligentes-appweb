<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoRuta extends Model
{
    use HasFactory;

    protected $fillable=[
        'vehiculo_id',
        'ruta_id',
    ];

    protected $casts = [
        'dias_activos' => 'array', // Cast para convertir a y desde JSON
    ];


    // Relación con Vehiculo
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
 
    // Relación con Ruta
    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }
}
