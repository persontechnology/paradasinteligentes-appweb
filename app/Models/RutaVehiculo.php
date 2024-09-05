<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RutaVehiculo extends Model
{
    use HasFactory;

    protected $fillable=[
        'vehiculo_id',
        'ruta_id',
        'dias_activos'
    ];

    protected $casts = [
        'dias_activos' => 'array'
    ];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class);
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
}
