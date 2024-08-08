<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaParada extends Model
{
    use HasFactory;

    protected $fillable=[
        'latitud',
        'longitud',
        'ruta_id',
        'parada_id',
        'tiempo_llegada',
        'numero'
    ];

   

    
    // Parada <- RutaParada
    public function parada()
    {
        return $this->belongsTo(Parada::class);
    }
}
