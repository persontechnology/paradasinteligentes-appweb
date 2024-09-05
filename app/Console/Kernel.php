<?php

namespace App\Console;

use App\Console\Schedulers\VehiculosScheduler;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // llamar a VehiculosSheduler para ejecutra commando para obtener vehculos y actualizar ubicacion
        // Llama al método schedule de VehiculosScheduler
        $vehiculosScheduler = new VehiculosScheduler();
        $vehiculosScheduler->schedule($schedule);
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
