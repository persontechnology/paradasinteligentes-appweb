<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoRuta extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo', 
        'ruta_id', 
        'inicio', 
        'finaliza', 
        'tiempo_total', 
        'detalle_recorrido',
        'coordenadas'
    ];  

    protected $casts = [
        'coordenadas' => 'array'
    ];

    
    // Relación con Ruta
    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }

    // Relación con Recorridos
    public function recorridos()
    {
        return $this->hasMany(Recorrido::class)->orderBy('orden');
    }

    // Relación con Paradas a través de Recorridos
    public function paradas()
    {
        return $this->belongsToMany(Parada::class, 'recorridos')->withPivot('orden')->orderBy('pivot_orden')->withTimestamps();
    }
    
}
