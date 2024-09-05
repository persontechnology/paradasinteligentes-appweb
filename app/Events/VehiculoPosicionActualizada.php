<?php

namespace App\Events;

use App\Models\PosicionVehiculo;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VehiculoPosicionActualizada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $posicionVehiculo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PosicionVehiculo $posicionVehiculo)
    {
        // Cargar relaciones necesarias
        $this->posicionVehiculo = $posicionVehiculo->load([
            'vehiculo', 
            'subRuta.paradaInicio', 
            'subRuta.paradaFinal'
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\PrivateChannel|\Illuminate\Broadcasting\PresenceChannel
     */
    public function broadcastOn()
    {
        return new Channel('vehiculos');
    }
}
