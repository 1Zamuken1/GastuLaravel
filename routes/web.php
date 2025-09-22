<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ProyeccionIngresoController;
use App\Http\Controllers\ProyeccionEgresoController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\AhorroMetaController;
use App\Http\Controllers\AporteAhorroController;

use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\UsuarioController;

Route::get('/', function () {
    return view('welcome');
});

//Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

//Route::middleware(['auth'])->group(function () { 
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

    //Proyecciones de Ingresos
    Route::post('/proyecciones', [ProyeccionEgresoController::class, 'store'])->name('proyecciones.store');
    Route::put('/proyecciones/{id}', [ProyeccionEgresoController::class, 'update'])->name('proyecciones.update');
    Route::delete('/proyecciones/{id}', [ProyeccionEgresoController::class, 'destroy'])->name('proyecciones.destroy');
    Route::get('/proyecciones/{id}', [ProyeccionEgresoController::class, 'show'])->name('proyecciones.show');
    Route::get('/proyecciones/para-confirmar', [ProyeccionEgresoController::class, 'proyeccionesParaConfirmar']);
    Route::post('/proyecciones/confirmar', [ProyeccionEgresoController::class, 'confirmarRecurrencias']);
    
   //Egresos
    Route::get('/egresos', [EgresoController::class, 'index'])->name('egresos.index');
    Route::get('/egresos/create/{id?}', [EgresoController::class, 'create'])->name('egresos.create');

    Route::post('/egresos/store', [EgresoController::class, 'store'])->name('egresos.store');
    Route::post('/egresos/update/{id}', [EgresoController::class, 'update'])->name('egresos.update');
    Route::delete('/egresos/destroy/{id}', [EgresoController::class, 'destroy'])->name('egresos.destroy');

 // Ahorros

Route::get('/ahorros', [AhorroMetaController::class, 'index'])->name('ahorros.index');
// Modal Crear AhorroMeta
Route::get('/ahorros/create', [AhorroMetaController::class, 'create'])->name('ahorros.create');
Route::post('/ahorros/store', [AhorroMetaController::class, 'store'])->name('ahorros.store');
// Modal Editar AhorroMeta
Route::post('/ahorros/update/{id}', [AhorroMetaController::class, 'update'])->name('ahorros.update');
// Modal Eliminar AhorroMeta
Route::delete('/ahorros/destroy/{id}', [AhorroMetaController::class, 'destroy'])->name('ahorros.destroy');
// Modal Mostrar AhorroMeta (detalle con % avance y aportes)
Route::get('/ahorros/show/{id}', [AhorroMetaController::class, 'show'])->name('ahorros.show');
// Modal Index Aportes de un Ahorro
Route::get('/ahorros/{id}/aportes', [AporteAhorroController::class, 'index'])->name('aportes.index');
// Modal Editar Aporte
Route::post('/ahorros/aportes/update/{id}', [AporteAhorroController::class, 'update'])->name('aportes.update');



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




