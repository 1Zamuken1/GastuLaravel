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










// ================================== Rutas para Ahorros (AhorroMeta) =================================================
Route::get('/ahorros', [AhorroMetaController::class, 'index']);
Route::get('/ahorros/{id}', [AhorroMetaController::class, 'show']);
Route::post('/ahorros', [AhorroMetaController::class, 'store']);
Route::put('/ahorros/{id}', [AhorroMetaController::class, 'update']);
Route::patch('/ahorros/{id}', [AhorroMetaController::class, 'updatePartial']);
Route::delete('/ahorros/{id}', [AhorroMetaController::class, 'destroy']);

// ============================= Rutas para Ahorros Programados ========================================================
Route::get('/ahorros-programados', [AhorroProgramadoController::class, 'index']);
Route::get('/ahorros-programados/{id}', [AhorroProgramadoController::class, 'show']);
Route::post('/ahorros-programados', [AhorroProgramadoController::class, 'store']);
Route::put('/ahorros-programados/{id}', [AhorroProgramadoController::class, 'update']);
Route::patch('/ahorros-programados/{id}', [AhorroProgramadoController::class, 'updatePartial']);
Route::delete('/ahorros-programados/{id}', [AhorroProgramadoController::class, 'destroy']);

// ============================= Rutas para Aportes de Ahorro ==========================================================
Route::get('/aportes-ahorro', [AporteAhorroController::class, 'index']);
Route::get('/aportes-ahorro/{id}', [AporteAhorroController::class, 'show']);
Route::post('/aportes-ahorro', [AporteAhorroController::class, 'store']);
Route::put('/aportes-ahorro/{id}', [AporteAhorroController::class, 'update']);
Route::patch('/aportes-ahorro/{id}', [AporteAhorroController::class, 'updatePartial']);
Route::delete('/aportes-ahorro/{id}', [AporteAhorroController::class, 'destroy']);
