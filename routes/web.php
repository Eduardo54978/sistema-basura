<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
Route::get('/reportes/conteo', [ReporteController::class, 'conteoCategoria'])->name('reportes.conteo');
Route::get('/reportes/fecha', [ReporteController::class, 'analisisFecha'])->name('reportes.fecha');
Route::get('/reportes/3d', [ReporteController::class, 'visualizacion3d'])->name('reportes.3d');