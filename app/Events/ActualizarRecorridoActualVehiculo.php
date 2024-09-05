<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActualizarRecorridoActualVehiculo implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $posicion;
    public function __construct($posicionVehiculo)
    {
        $this->posicion = $posicionVehiculo->load(['tipoRuta', 'tipoRuta.paradas', 'tipoRuta.ruta', 'vehiculo']);
    }

   
    public function broadcastOn()
    {
        return new Channel('actualizar_recorrido_actual_vehiculo');
    }
}
