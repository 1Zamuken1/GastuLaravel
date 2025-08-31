<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ConceptoIngresoController;


// Ingresos
Route::get('/ingresos', [IngresoController::class, 'index']);

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
