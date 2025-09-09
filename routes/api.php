<?php
// controladores 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ConceptoIngresoController;
use App\Http\Controllers\ProyeccionIngresoController;
use App\Http\Controllers\GastosController;  
use App\Http\Controllers\ConceptoEgresoController;
use App\Http\Controllers\AhorroMetaController;
use App\Http\Controllers\AhorroProgramadoController;
use App\Http\Controllers\AporteAhorroController;


// ================================== Rutas para Ingresos ==================================================================================================================
Route::get('/ingresos', [IngresoController::class, 'index']);

//pruebas
Route::get('/ingresos/full', [IngresoController::class, 'indexFull']);

Route::get('/ingresos/{id}', [IngresoController::class, 'show']);

Route::post('/ingresos', [IngresoController::class, 'store']);

Route::put('/ingresos/{id}', [IngresoController::class, 'update']);

Route::patch('/ingresos/{id}', [IngresoController::class, 'updatePartial']);

Route::delete('/ingresos/{id}', [IngresoController::class, 'destroy']);

// ================================= Rutas para Conceptos de Ingreso ==================================================================================================================
Route::get('/conceptos-ingreso', [ConceptoIngresoController::class, 'index']);

Route::get('/conceptos-ingreso/{id}', [ConceptoIngresoController::class, 'show']);

Route::post('/conceptos-ingreso', [ConceptoIngresoController::class, 'store']);

Route::put('/conceptos-ingreso/{id}', [ConceptoIngresoController::class, 'update']);

Route::patch('/conceptos-ingreso/{id}', [ConceptoIngresoController::class, 'updatePartial']);

Route::delete('/conceptos-ingreso/{id}', [ConceptoIngresoController::class, 'destroy']);

// ============================= Rutas para Proyecciones de Ingreso ==================================================================================================================
Route::get('/proyecciones-ingreso', [ProyeccionIngresoController::class, 'index']);

Route::get('/proyecciones-ingreso/{id}', [ProyeccionIngresoController::class, 'show']);

Route::post('/proyecciones-ingreso', [ProyeccionIngresoController::class, 'store']);

Route::put('/proyecciones-ingreso/{id}', [ProyeccionIngresoController::class, 'update']);

Route::patch('/proyecciones-ingreso/{id}', [ProyeccionIngresoController::class, 'updatePartial']);

Route::delete('/proyecciones-ingreso/{id}', [ProyeccionIngresoController::class, 'destroy']);

// // ============================= Rutas para Gastos ==================================================================================================================
// Route::get('/gastos', [GastosController::class, 'index']);
// Route::get('/gastos/{gastos}', [GastosController::class, 'show']);
// Route::post('/gastos', [GastosController::class, 'store']);
// Route::put('/gastos/{gastos}', [GastosController::class, 'update']);
// Route::delete('/gastos/{gastos}', [GastosController::class, 'destroy']);

// // ============================= Rutas para Conceptos de Egreso ==================================================================================================================
// Route::get('/conceptoEgresos', [ConceptoEgresoController::class, 'index']);
// Route::get('/conceptoEgresos/{conceptoEgreso}', [ConceptoEgresoController::class, 'show']);
// Route::post('/conceptoEgresos', [ConceptoEgresoController::class, 'store']);
// Route::put('/conceptoEgresos/{conceptoEgreso}', [ConceptoEgresoController::class, 'update']);
// Route::delete('/conceptoEgresos/{conceptoEgreso}', [ConceptoEgresoController::class, 'destroy']);

// ============================= Rutas para Ahorro Meta =========================================================================================================================
Route::get('/ahorro-meta', [AhorroMetaController::class, 'index']); // GET trae

Route::get('/ahorro-meta/{id}', [AhorroMetaController::class, 'show']); // GET trae

Route::post('/ahorro-meta', [AhorroMetaController::class, 'store']); // POST crea

Route::put('/ahorro-meta/{id}', [AhorroMetaController::class, 'update']); // PUT actualiza

Route::patch('/ahorro-meta/{id}', [AhorroMetaController::class, 'updatePartial']); // PATCH actualiza parcialmente

Route::delete('/ahorro-meta/{id}', [AhorroMetaController::class, 'destroy']); // DELETE elimina

// ============================= Rutas para Ahorro Programado =========================================================================================================================
Route::get('/ahorro-programado', [AhorroProgramadoController::class, 'index']); 

Route::get('/ahorro-programado/{id}', [AhorroProgramadoController::class, 'show']); 

Route::post('/ahorro-programado', [AhorroProgramadoController::class, 'store']); 

Route::put('/ahorro-programado/{id}', [AhorroProgramadoController::class, 'update']);

Route::patch('/ahorro-programado/{id}', [AhorroProgramadoController::class, 'updatePartial']);

Route::delete('/ahorro-programado/{id}', [AhorroProgramadoController::class, 'destroy']);

// ============================= Rutas para Aporte Ahorro =========================================================================================================================
Route::get('aporte-ahorro', [AporteAhorroController::class, 'index']);

Route::get('aporte-ahorro/{id}', [AporteAhorroController::class, 'show']);

Route::post('aporte-ahorro', [AporteAhorroController::class, 'store']);

Route::put('aporte-ahorro/{id}', [AporteAhorroController::class, 'update']);

Route::patch('aporte-ahorro/{id}', [AporteAhorroController::class, 'updatePartial']);

Route::delete('aporte-ahorro/{id}', [AporteAhorroController::class, 'destroy']);
