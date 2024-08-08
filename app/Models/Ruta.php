<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'estado'];

    public function subRutas()
    {
        return $this->hasMany(SubRuta::class);
    } 

     // Relación muchos a muchos con Vehiculo
    public function vehiculos()
    {
        return $this->belongsToMany(Vehiculo::class, 'vehiculo_rutas')->withPivot('dias_activos');
    }
}
