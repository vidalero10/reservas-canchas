<?php

use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ReservaController::class, 'index'])->name('inicio');
Route::get('/reservas/dia', [ReservaController::class, 'reservasDelDia'])->name('reservas.del_dia');
Route::get('/reservas/crear', [ReservaController::class, 'create'])->name('reservas.create');
Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
Route::post('/reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
Route::get('/reportes/ingresos', [ReservaController::class, 'reporteIngresos'])->name('reportes.ingresos');
