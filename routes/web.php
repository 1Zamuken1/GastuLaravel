<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ProyeccionIngresoController;
use App\Http\Controllers\ProyeccionEgresoController;
use App\Http\Controllers\EgresoController;

use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\IAController;
use App\Http\Controllers\AhorroMetaController;
use App\Http\Controllers\AporteAhorroController;
use App\Http\Controllers\Admin\UsuarioController;

Route::get('/', function () {
    return view('landing.landing');
});

Route::get('/funciones', fn() => view('landing.funciones'));
Route::get('/uso', fn() => view('landing.uso'));

//Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('groq.auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
    
   //Egresos
    Route::get('/egresos', [EgresoController::class, 'index'])->name('egresos.index');
    Route::get('/egresos/create/{id?}', [EgresoController::class, 'create'])->name('egresos.create');

    Route::post('/egresos/store', [EgresoController::class, 'store'])->name('egresos.store');
    Route::post('/egresos/update/{id}', [EgresoController::class, 'update'])->name('egresos.update');
    Route::delete('/egresos/destroy/{id}', [EgresoController::class, 'destroy'])->name('egresos.destroy');

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

// Rutas para IA - Requieren autenticación
Route::middleware('groq.auth')->group(function () {
    Route::get('/ia', [IAController::class, 'index'])->name('ia.chat');
    Route::post('/ia/mensaje', [IAController::class, 'procesarMensaje'])->name('ia.mensaje');
    Route::get('/ia/estadisticas', [IAController::class, 'obtenerEstadisticas'])->name('ia.estadisticas');
    Route::post('/ia/consulta', [IAController::class, 'consultaPersonalizada'])->name('ia.consulta');
});
// Rutas Ahorro Meta protegidas
// Rutas Ahorros protegidas
Route::middleware('groq.auth')->prefix('ahorros')->name('ahorros.')->group(function () {
    Route::post('/store', [AhorroMetaController::class, 'store'])->name('store');
    Route::get('/', [AhorroMetaController::class, 'index'])->name('index');
    Route::get('/{id}', [AhorroMetaController::class, 'show'])->name('show');
    Route::put('/{id}', [AhorroMetaController::class, 'update'])->name('update');
    Route::delete('/{id}', [AhorroMetaController::class, 'destroy'])->name('destroy');
});

// Rutas Aportes protegidas
Route::middleware('groq.auth')->prefix('aportes')->name('aportes.')->group(function () {
    Route::get('/{ahorro_meta_id}', [AporteAhorroController::class, 'index'])->name('index');
    Route::put('/{id}', [AporteAhorroController::class, 'update'])->name('update');
    Route::post('/{id}/aportar-asignado', [AporteAhorroController::class, 'aportarAsignado'])->name('aportar-asignado');
});

// Rutas Aportes protegidas
Route::middleware('groq.auth')->prefix('aportes')->name('aportes.')->group(function () {
    Route::get('/{ahorro_meta_id}', [AporteAhorroController::class, 'index'])->name('index');
    Route::put('/{id}', [AporteAhorroController::class, 'update'])->name('update');
    Route::post('/{id}/aportar-asignado', [AporteAhorroController::class, 'aportarAsignado'])->name('aportar-asignado');
});

// Rutas protegidas para ingresos y proyecciones de ingresos
Route::middleware('groq.auth')->group(function () {
    // Ingresos
    Route::get('/ingresos', [IngresoController::class, 'index'])->name('ingresos.index');
    Route::get('/ingresos/create/{id?}', [IngresoController::class, 'create'])->name('ingresos.create');
    Route::post('/ingresos/store', [IngresoController::class, 'store'])->name('ingresos.store');
    Route::post('/ingresos/update/{id}', [IngresoController::class, 'update'])->name('ingresos.update');
    Route::delete('/ingresos/destroy/{id}', [IngresoController::class, 'destroy'])->name('ingresos.destroy');

    // Proyecciones de Ingresos
    Route::post('/proyecciones_ingresos', [ProyeccionIngresoController::class, 'store'])->name('proyecciones_ingresos.store');
    Route::put('/proyecciones_ingresos/{id}', [ProyeccionIngresoController::class, 'update'])->name('proyecciones_ingresos.update');
    Route::delete('/proyecciones_ingresos/{id}', [ProyeccionIngresoController::class, 'destroy'])->name('proyecciones_ingresos.destroy');
    Route::get('/proyecciones_ingresos/recordatorio-hoy', [ProyeccionIngresoController::class, 'proyeccionesRecordatorioHoy']);
    Route::get('/proyecciones_ingresos/para-confirmar', [ProyeccionIngresoController::class, 'proyeccionesParaConfirmar']);
    Route::post('/proyecciones_ingresos/confirmar', [ProyeccionIngresoController::class, 'confirmarRecurrencias']);
});
// Rutas protegidas para egresos y proyecciones de egresos
Route::middleware('groq.auth')->group(function () {
    // Egresos
    Route::get('/egresos', [EgresoController::class, 'index'])->name('egresos.index');
    Route::get('/egresos/create/{id?}', [EgresoController::class, 'create'])->name('egresos.create');
    Route::post('/egresos/store', [EgresoController::class, 'store'])->name('egresos.store');
    Route::post('/egresos/update/{id}', [EgresoController::class, 'update'])->name('egresos.update');
    Route::delete('/egresos/destroy/{id}', [EgresoController::class, 'destroy'])->name('egresos.destroy');

    // Proyecciones de Egresos
    Route::post('/proyecciones_egresos', [ProyeccionEgresoController::class, 'store'])->name('proyecciones_egresos.store');
    Route::put('/proyecciones_egresos/{id}', [ProyeccionEgresoController::class, 'update'])->name('proyecciones_egresos.update');
    Route::delete('/proyecciones_egresos/{id}', [ProyeccionEgresoController::class, 'destroy'])->name('proyecciones_egresos.destroy');
    Route::get('/proyecciones_egresos/recordatorio-hoy', [ProyeccionEgresoController::class, 'proyeccionesRecordatorioHoy']);
    Route::get('/proyecciones_egresos/para-confirmar', [ProyeccionEgresoController::class, 'proyeccionesParaConfirmar']);
    Route::post('/proyecciones_egresos/confirmar', [ProyeccionEgresoController::class, 'confirmarRecurrencias']);
});

Route::middleware('groq.auth')->group(function () {
    Route::resource('usuarios', UsuarioController::class);
});
