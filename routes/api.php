<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ConceptoIngresoController;
use App\Http\Controllers\ProyeccionIngresoController;


// Ingresos
Route::get('/ingresos', [IngresoController::class, 'index']);

//pruebas
Route::get('/ingresos/full', [IngresoController::class, 'indexFull']);

Route::get('/ingresos/{id}', [IngresoController::class, 'show']);

Route::post('/ingresos', [IngresoController::class, 'store']);

Route::put('/ingresos/{id}', [IngresoController::class, 'update']);

Route::patch('/ingresos/{id}', [IngresoController::class, 'updatePartial']);

Route::delete('/ingresos/{id}', [IngresoController::class, 'destroy']);

// Conceptos de Ingreso
Route::get('/conceptos-ingreso', [ConceptoIngresoController::class, 'index']);

Route::get('/conceptos-ingreso/{id}', [ConceptoIngresoController::class, 'show']);

Route::post('/conceptos-ingreso', [ConceptoIngresoController::class, 'store']);

Route::put('/conceptos-ingreso/{id}', [ConceptoIngresoController::class, 'update']);

Route::patch('/conceptos-ingreso/{id}', [ConceptoIngresoController::class, 'updatePartial']);

Route::delete('/conceptos-ingreso/{id}', [ConceptoIngresoController::class, 'destroy']);

// Proyecciones de Ingreso
Route::get('/proyecciones-ingreso', [ProyeccionIngresoController::class, 'index']);

Route::get('/proyecciones-ingreso/{id}', [ProyeccionIngresoController::class, 'show']);

Route::post('/proyecciones-ingreso', [ProyeccionIngresoController::class, 'store']);

Route::put('/proyecciones-ingreso/{id}', [ProyeccionIngresoController::class, 'update']);

Route::patch('/proyecciones-ingreso/{id}', [ProyeccionIngresoController::class, 'updatePartial']);

Route::delete('/proyecciones-ingreso/{id}', [ProyeccionIngresoController::class, 'destroy']);
