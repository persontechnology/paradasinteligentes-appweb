<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubRuta extends Model
{
    use HasFactory;
    protected $fillable = [
        'ruta_id',
        'parada_inicio_id',
        'parada_final_id',
        'tiempo_recorrido',
        'coordenadas'
    ];

    protected $casts = [
        'coordenadas' => 'array'
    ];
    

    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }

    public function paradaInicio()
    {
        return $this->belongsTo(Parada::class, 'parada_inicio_id');
    }

    public function paradaFinal()
    {
        return $this->belongsTo(Parada::class, 'parada_final_id');
    }
}
