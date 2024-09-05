<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'estado',
        'distancia_total',
        'tiempo_total_ruta'
    ];

    
    public function vehiculos()
    {
        return $this->belongsToMany(Vehiculo::class, 'ruta_vehiculos')->withPivot('dias_activos');
    }
 
  
    public function tipoRutaIda()
    {
        return $this->hasOne(TipoRuta::class)->where('tipo', 'IDA');
    }

    public function tipoRutaRetorno()
    {
        return $this->hasOne(TipoRuta::class)->where('tipo', 'RETORNO');
    }
    
    
}
