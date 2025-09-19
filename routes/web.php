<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ProyeccionIngresoController;
use App\Http\Controllers\ProyeccionEgresoController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\AhorroMetaController;
use App\Http\Controllers\AutenticacionController;

Route::get('/', function () {
    return view('welcome');
});


/**Route::get('/ahorros', function () {
    return view('ahorros.AhorroMeta');
}); **/

//vistas protegidas
//Route::middleware('auth')->group(function () {

    //Ingresos
    Route::get('/ingresos', [IngresoController::class, 'index'])->name('ingresos.index');
    //Route::post('/ingresos', [IngresoController::class, 'store'])->name('ingresos.store');
    Route::get('/ingresos/create/{id?}', [IngresoController::class, 'create'])->name('ingresos.create');

    Route::post('/ingresos/store', [IngresoController::class, 'store'])->name('ingresos.store');
    Route::post('/ingresos/update/{id}', [IngresoController::class, 'update'])->name('ingresos.update');
    // Para ingresos reales
    Route::delete('/ingresos/destroy/{id}', [IngresoController::class, 'destroy'])->name('ingresos.destroy');

    //Proyecciones de Ingresos
    Route::post('/proyecciones', [ProyeccionIngresoController::class, 'store'])->name('proyecciones.store');
    Route::put('/proyecciones/{id}', [ProyeccionIngresoController::class, 'update'])->name('proyecciones.update');
    // Para proyecciones
    Route::delete('/proyecciones/{id}', [ProyeccionIngresoController::class, 'destroy'])->name('proyecciones.destroy');
    Route::get('/proyecciones/{id}', [ProyeccionIngresoController::class, 'show'])->name('proyecciones.show');
    Route::get('/proyecciones/para-confirmar', [ProyeccionIngresoController::class, 'proyeccionesParaConfirmar']);
    Route::post('/proyecciones/confirmar', [ProyeccionIngresoController::class, 'confirmarRecurrencias']);

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

  /*  // ahorro meta
    Route::prefix('ahorros')->group(function () {
        // Mostrar todos los ahorros (index)
        Route::get('/', [AhorroMetaController::class, 'index'])->name('ahorros.index');

        // Formulario para crear nuevo ahorro
        Route::get('/create', [AhorroMetaController::class, 'create'])->name('ahorros.create');

        // Guardar nuevo ahorro
        Route::post('/', [AhorroMetaController::class, 'store'])->name('ahorros.store');

        // Ver detalle de un ahorro
        Route::get('/{id}', [AhorroMetaController::class, 'show'])->name('ahorros.show');

        // Eliminar ahorro
        Route::delete('/{id}', [AhorroMetaController::class, 'destroy'])->name('ahorros.destroy');

    });
//}); */

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

//Registro de usuario
Route::post('/registrar', [AutenticacionController::class, 'registrar'])->name('registrar');

// Login de usuario
Route::post('/login', [AutenticacionController::class, 'login'])->name('login');

// Logout de usuario
Route::post('/logout', [AutenticacionController::class, 'logout'])->name('logout')->middleware('auth');















// ================================== Vistas protegidas de Ahorros ================================================================

// AhorroMeta
Route::get('/ahorros', [AhorroMetaController::class, 'index'])->name('ahorros.index');
Route::get('/ahorros/create/{id?}', [AhorroMetaController::class, 'create'])->name('ahorros.create');
Route::post('/ahorros/store', [AhorroMetaController::class, 'store'])->name('ahorros.store');
Route::get('/ahorros/{id}', [AhorroMetaController::class, 'show'])->name('ahorros.show');
Route::get('/ahorros/{id}/edit', [AhorroMetaController::class, 'edit'])->name('ahorros.edit');
Route::post('/ahorros/update/{id}', [AhorroMetaController::class, 'update'])->name('ahorros.update');
Route::delete('/ahorros/destroy/{id}', [AhorroMetaController::class, 'destroy'])->name('ahorros.destroy');

// AhorroProgramado
Route::get('/ahorros/{ahorroMetaId}/programados', [AhorroProgramadoController::class, 'index'])->name('programados.index');
Route::get('/programados/{id}', [AhorroProgramadoController::class, 'show'])->name('programados.show');
Route::post('/programados/store', [AhorroProgramadoController::class, 'store'])->name('programados.store');
Route::post('/programados/update/{id}', [AhorroProgramadoController::class, 'update'])->name('programados.update');
Route::delete('/programados/destroy/{id}', [AhorroProgramadoController::class, 'destroy'])->name('programados.destroy');

// AporteAhorro
Route::get('/ahorros/{ahorroMetaId}/aportes', [AporteAhorroController::class, 'index'])->name('aportes.index');
Route::get('/aportes/{id}', [AporteAhorroController::class, 'show'])->name('aportes.show');
Route::post('/aportes/store', [AporteAhorroController::class, 'store'])->name('aportes.store');
Route::post('/aportes/update/{id}', [AporteAhorroController::class, 'update'])->name('aportes.update');
Route::delete('/aportes/destroy/{id}', [AporteAhorroController::class, 'destroy'])->name('aportes.destroy');
