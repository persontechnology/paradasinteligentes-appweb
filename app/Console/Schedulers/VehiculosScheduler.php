<?php

namespace App\Console\Schedulers;

use Illuminate\Console\Scheduling\Schedule;
use App\Models\Configuracion;

class VehiculosScheduler
{
    public function schedule(Schedule $schedule)
    {
        // Obtener la configuración de la base de datos
        $configuracion = Configuracion::first();
        $frecuencia = $configuracion ? $configuracion->frecuencia : 'everyTenMinutes';

        switch ($frecuencia) {
            case 'every5Seconds':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyFiveSeconds();
                break;
            case 'every10Seconds':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyTenSeconds();
                break;
            case 'every15Seconds':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyFifteenSeconds();
                break;
            case 'every20Seconds':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyTwentySeconds();
                break;
            case 'every25Seconds':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyTwentyFiveSeconds();
                break;
            case 'every30Seconds':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyThirtySeconds();
                break;
            case 'every35Seconds':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyThirtyFiveSeconds();
                break;
            case 'every40Seconds':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyFortySeconds();
                break;
            case 'every45Seconds':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyFortyFiveSeconds();
                break;
            case 'every50Seconds':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyFiftySeconds();
                break;
            case 'every55Seconds':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyFiftyFiveSeconds();
                break;
            // Aquí mantienes las opciones ya existentes
            case 'everyMinute':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyMinute();
                break;
            case 'everyFiveMinutes':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyFiveMinutes();
                break;
            case 'everyTenMinutes':
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyTenMinutes();
                break;
            // Y así sucesivamente...
            default:
                $schedule->command('app:obtener-y-actualizar-vehiculos-api-rest-ecuatrack')->everyTenMinutes();
                break;
        }
    }
}
