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
    ];

    

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
