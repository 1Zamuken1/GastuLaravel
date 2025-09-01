<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ingresos', function () {
    return view('ingresos');
});
Route::get('/gastos', function () {
    return view('gastos');
});

Route::get('/gastos', [App\Http\Controllers\GastosController::class, 'index'])->name('gastos.index');
Route::post('/gastos', [App\Http\Controllers\GastosController::class, 'store'])->name('gastos.store');
