<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ProyeccionIngresoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/gastos', function () {
    return view('gastos');
});


//Ingresos
Route::get('/ingresos', [IngresoController::class, 'index'])->name('ingresos.index');
//Route::post('/ingresos', [IngresoController::class, 'store'])->name('ingresos.store');
Route::post('/ingresos/store', [IngresoController::class, 'store'])->name('ingresos.store');
Route::post('/ingresos/update/{id}', [IngresoController::class, 'update'])->name('ingresos.update');
Route::delete('/ingresos/destroy/{id}', [IngresoController::class, 'destroy'])->name('ingresos.destroy');

//Proyecciones de Ingresos
Route::post('/proyecciones', [ProyeccionIngresoController::class, 'store'])->name('proyecciones.store');
Route::put('/proyecciones/{id}', [ProyeccionIngresoController::class, 'update'])->name('proyecciones.update');
Route::delete('/proyecciones/{id}', [ProyeccionIngresoController::class, 'destroy'])->name('proyecciones.destroy');
Route::get('/proyecciones/{id}', [ProyeccionIngresoController::class, 'show'])->name('proyecciones.show');


Route::get('/gastos', [App\Http\Controllers\GastosController::class, 'index'])->name('gastos.index');
Route::post('/gastos', [App\Http\Controllers\GastosController::class, 'store'])->name('gastos.store');
