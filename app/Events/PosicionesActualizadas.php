<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PosicionesActualizadas implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $posiciones;

    public function __construct($posiciones)
    {
        $this->posiciones = $posiciones;
    }

    public function broadcastOn()
    {
        return new Channel('posiciones-vehiculos');
    }
}
