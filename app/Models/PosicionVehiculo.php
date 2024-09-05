<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosicionVehiculo extends Model
{
    use HasFactory;

    protected $fillable=[
        'coordenadas',
        'esta_ruta',
        'tipo_ruta_id',
        'detalle',
        'vehiculo_id',
        'direccion',
        'velocidad'
    ];

    protected $casts = [
        'coordenadas' => 'array',
        'velocidad'=>'decimal:2',
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function tipoRuta()
    {
        return $this->belongsTo(TipoRuta::class);
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }


}
