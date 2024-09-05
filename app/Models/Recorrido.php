<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recorrido extends Model
{
    use HasFactory;
    protected $fillable = ['tipo_ruta_id', 'parada_id', 'orden'];

    // Relación con TipoRuta
    public function tipoRuta()
    {
        return $this->belongsTo(TipoRuta::class);
    }

    // Relación con Parada
    public function parada()
    {
        return $this->belongsTo(Parada::class);
    }
    
}
