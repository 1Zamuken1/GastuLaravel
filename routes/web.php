<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ProyeccionIngresoController;
use App\Http\Controllers\ProyeccionEgresoController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\AhorroMetaController;
use App\Http\Controllers\AhorroProgramadoController;
use App\Http\Controllers\AporteAhorroController;

use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\UsuarioController;

Route::get('/', function () {
    return view('welcome');
});

    //Ingresos
    Route::get('/ingresos', [IngresoController::class, 'index'])->name('ingresos.index');
    //Route::post('/ingresos', [IngresoController::class, 'store'])->name('ingresos.store');
    Route::get('/ingresos/create/{id?}', [IngresoController::class, 'create'])->name('ingresos.create');

    Route::post('/ingresos/store', [IngresoController::class, 'store'])->name('ingresos.store');
    Route::post('/ingresos/update/{id}', [IngresoController::class, 'update'])->name('ingresos.update');
    // Para ingresos reales
    Route::delete('/ingresos/destroy/{id}', [IngresoController::class, 'destroy'])->name('ingresos.destroy');

    //Proyecciones de Ingresos
    Route::post('/proyecciones_ingresos', [ProyeccionIngresoController::class, 'store'])->name('proyecciones_ingresos.store');
    Route::put('/proyecciones_ingresos/{id}', [ProyeccionIngresoController::class, 'update'])->name('proyecciones_ingresos.update');
    // Para proyecciones
    Route::delete('/proyecciones_ingresos/{id}', [ProyeccionIngresoController::class, 'destroy'])->name('proyecciones_ingresos.destroy');

    Route::get('/proyecciones_ingresos/recordatorio-hoy', [ProyeccionIngresoController::class, 'proyeccionesRecordatorioHoy']);
    Route::get('/proyecciones_ingresos/para-confirmar', [ProyeccionIngresoController::class, 'proyeccionesParaConfirmar']);
    Route::post('/proyecciones_ingresos/confirmar', [ProyeccionIngresoController::class, 'confirmarRecurrencias']);

   //Egresos
    Route::get('/egresos', [EgresoController::class, 'index'])->name('egresos.index');
    //Route::post('/ingresos', [IngresoController::class, 'store'])->name('ingresos.store');
    Route::get('/egresos/create/{id?}', [EgresoController::class, 'create'])->name('egresos.create');

    Route::post('/egresos/store', [EgresoController::class, 'store'])->name('egresos.store');
    Route::post('/egresos/update/{id}', [EgresoController::class, 'update'])->name('egresos.update');
    Route::delete('/egresos/destroy/{id}', [EgresoController::class, 'destroy'])->name('egresos.destroy');

    //Proyecciones de Ingresos
    Route::post('/proyecciones', [ProyeccionEgresoController::class, 'store'])->name('proyecciones.store');
    Route::put('/proyecciones/{id}', [ProyeccionEgresoController::class, 'update'])->name('proyecciones.update');
    Route::delete('/proyecciones/{id}', [ProyeccionEgresoController::class, 'destroy'])->name('proyecciones.destroy');
    Route::get('/proyecciones/{id}', [ProyeccionEgresoController::class, 'show'])->name('proyecciones.show');
    Route::get('/proyecciones/para-confirmar', [ProyeccionEgresoController::class, 'proyeccionesParaConfirmar']);
    Route::post('/proyecciones/confirmar', [ProyeccionEgresoController::class, 'confirmarRecurrencias']);

    // // AhorroMeta
    // Route::get('/ahorros', [AhorroMetaController::class, 'index'])->name('ahorros.index');
    // Route::get('/ahorros/create', [AhorroMetaController::class, 'create'])->name('ahorros.create');
    // Route::post('/ahorros/store', [AhorroMetaController::class, 'store'])->name('ahorros.store');
    // Route::get('/ahorros/{id}', [AhorroMetaController::class, 'show'])->name('ahorros.show');
    // Route::get('/ahorros/{id}/edit', [AhorroMetaController::class, 'edit'])->name('ahorros.edit');
    // Route::put('/ahorros/update/{id}', [AhorroMetaController::class, 'update'])->name('ahorros.update');
    // Route::delete('/ahorros/destroy/{id}', [AhorroMetaController::class, 'destroy'])->name('ahorros.destroy');

    //AhorroProgramado
    Route::get('/ahorros/{ahorroMetaId}/programados', [AhorroProgramadoController::class, 'index'])->name('programados.index');
    Route::get('/programados/{id}', [AhorroProgramadoController::class, 'show'])->name('programados.show');
    Route::post('/programados/store', [AhorroProgramadoController::class, 'store'])->name('programados.store');
    Route::put('/programados/update/{id}', [AhorroProgramadoController::class, 'update'])->name('programados.update');
    Route::delete('/programados/destroy/{id}', [AhorroProgramadoController::class, 'destroy'])->name('programados.destroy');

    // AporteAhorro
    Route::get('/ahorros/{ahorroMetaId}/aportes', [AporteAhorroController::class, 'index'])->name('aportes.index');
    Route::get('/aportes/{id}', [AporteAhorroController::class, 'show'])->name('aportes.show');
    Route::get('/aportes/{id}/edit', [AporteAhorroController::class, 'edit'])->name('aportes.edit');
    Route::put('/aportes/update/{id}', [AporteAhorroController::class, 'update'])->name('aportes.update');
    Route::put('/aportes/{id}/pagar-cuota', [AporteAhorroController::class, 'pagarCuota'])->name('aportes.pagarCuota');
    Route::delete('/aportes/destroy/{id}', [AporteAhorroController::class, 'destroy'])->name('aportes.destroy');


//Formularios de autenticación
Route::get('/registro', function () {
    return view('auth.registro');
})->name('registro.form');

Route::get('/login', function() {
    return view('auth.login');
})->name('login.form');

//procesamiento de formularios
Route::post('/registrar',[AutenticacionController::class, 'registrar'])->name('registrar');
Route::post('/login',[AutenticacionController::class, 'login'])->name('login');



// Logout de usuario
Route::post('/logout', [AutenticacionController::class, 'logout'])->name('logout')->middleware('auth');




