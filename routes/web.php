<?php

use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParadaAadmin;
use App\Http\Controllers\ParadaAdminController;
use App\Http\Controllers\ParadaController;
use App\Http\Controllers\PosicionVehiculoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::post('/vehiculo/{id}/update-location', [DashboardController::class, 'updateLocation'])->name('vehiculo.updateLocation');



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::apiResource('api-paradas', ParadaAdminController::class);
    Route::patch('/api-paradas/{id}/estado', [ParadaAdminController::class,'cambiarEstado']);


    // paradas
    Route::resource('paradas', ParadaController::class);
    Route::get('paradas-mapa', [ParadaController::class,'mapa'])->name('paradas.mapa');
    Route::put('/paradas/{id}/actualizar-coordenadas', [ParadaController::class, 'actualizarCoordenadas'])->name('paradas.actualizarCoordenadas');

    
    // rutas
    Route::resource('rutas', RutaController::class);
    Route::post('/rutas/{ruta}/actualizar-coordenadas', [RutaController::class, 'actualizarCoordenadas'])->name('rutas.actualizarCoordenadas');

    Route::delete('/subrutas/{id}', [RutaController::class, 'eliminarSubRuta'])->name('subrutas.destroy');

    // vehiculos
    Route::resource('vehiculos', VehiculoController::class);
    Route::get('/vehiculos/{vehiculo}/horario', [VehiculoController::class, 'horario'])->name('vehiculos.horario');
    Route::put('/vehiculos/{id}/horario', [VehiculoController::class,'horarioActualizar'])->name('vehiculos.horario.actualizar');
    Route::get('/vehiculos-ver-en-mapa', [VehiculoController::class, 'verEnMapa'])->name('vehiculos.veren.mapa');
    
    Route::get('/vehiculos/{id}/ubicacion', [VehiculoController::class, 'ubicacion'])->name('vehiculos.ubicacion');
    Route::get('/vehiculos/{id}/recorridos', [VehiculoController::class, 'recorridos'])->name('vehiculos.recorridos');
    Route::post('/vehiculos/{vehiculo}/actualizar-posicion', [DashboardController::class, 'actualizarPosicion'])->name('vehiculos.actualizarPosicion');

    Route::resource('poisicion-vehiculos',PosicionVehiculoController::class);

    
    // configuracion
    Route::resource('configuracion', ConfiguracionController::class);

});

require __DIR__.'/auth.php';
