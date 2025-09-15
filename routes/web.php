<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ProyeccionIngresoController;
use App\Http\Controllers\GastosController;
use App\Http\Controllers\AhorroMetaController;

Route::get('/', function () {
    return view('welcome');
});
            

Route::get('/ahorros', function () {
    return view('ahorros.AhorroMeta'); 
});


//Ingresos
Route::get('/ingresos', [IngresoController::class, 'index'])->name('ingresos.index');
//Route::post('/ingresos', [IngresoController::class, 'store'])->name('ingresos.store');
Route::get('/ingresos/create/{id?}', [IngresoController::class, 'create'])->name('ingresos.create');

Route::post('/ingresos/store', [IngresoController::class, 'store'])->name('ingresos.store');
Route::post('/ingresos/update/{id}', [IngresoController::class, 'update'])->name('ingresos.update');
Route::delete('/ingresos/destroy/{id}', [IngresoController::class, 'destroy'])->name('ingresos.destroy');

//Proyecciones de Ingresos
Route::post('/proyecciones', [ProyeccionIngresoController::class, 'store'])->name('proyecciones.store');
Route::put('/proyecciones/{id}', [ProyeccionIngresoController::class, 'update'])->name('proyecciones.update');
Route::delete('/proyecciones/{id}', [ProyeccionIngresoController::class, 'destroy'])->name('proyecciones.destroy');
Route::get('/proyecciones/{id}', [ProyeccionIngresoController::class, 'show'])->name('proyecciones.show');
Route::get('/proyecciones/para-confirmar', [ProyeccionIngresoController::class, 'proyeccionesParaConfirmar']);
Route::post('/proyecciones/confirmar', [ProyeccionIngresoController::class, 'confirmarRecurrencias']);

Route::get('/gastos', [App\Http\Controllers\GastosController::class, 'index'])->name('gastos.index');
Route::post('/gastos', [App\Http\Controllers\GastosController::class, 'store'])->name('gastos.store');
Route::get('/gastos/{id}', [App\Http\Controllers\GastosController::class, 'show'])->name('gastos.show');
Route::put('/gastos/{id}', [App\Http\Controllers\GastosController::class, 'update'])->name('gastos.update');
Route::delete('/gastos/{id}', [App\Http\Controllers\GastosController::class, 'destroy'])->name('gastos.destroy');
Route::resource('gastos', GastosController::class);

// ahorro meta
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
