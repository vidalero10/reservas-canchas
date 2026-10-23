<?php

use App\Http\Controllers\Admin\CanchaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ReservaController::class, 'index'])->name('inicio');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('register');
    Route::post('/registro', [AuthController::class, 'registro'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'rol:cliente'])->group(function () {
    Route::get('/mis-reservas', [ReservaController::class, 'misReservas'])->name('cliente.reservas.index');
    Route::get('/reservas/crear', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::post('/mis-reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar'])->name('cliente.reservas.cancelar');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'rol:admin'])->group(function () {
    Route::get('/reservas/dia', [ReservaController::class, 'reservasDelDia'])->name('reservas.del_dia');
    Route::post('/reservas/{reserva}/cancelar', [ReservaController::class, 'cancelarComoAdmin'])->name('reservas.cancelar');
    Route::get('/reportes/ingresos', [ReservaController::class, 'reporteIngresos'])->name('reportes.ingresos');
    Route::get('/canchas', [CanchaController::class, 'index'])->name('canchas.index');
    Route::get('/canchas/crear', [CanchaController::class, 'create'])->name('canchas.create');
    Route::post('/canchas', [CanchaController::class, 'store'])->name('canchas.store');
    Route::get('/canchas/{cancha}/editar', [CanchaController::class, 'edit'])->name('canchas.edit');
    Route::put('/canchas/{cancha}', [CanchaController::class, 'update'])->name('canchas.update');
    Route::patch('/canchas/{cancha}/estado', [CanchaController::class, 'cambiarEstado'])->name('canchas.estado');
});
