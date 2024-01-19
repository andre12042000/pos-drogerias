<?php

use App\Http\Controllers\Service\OrderServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Equipo\EquipoController;

Route::get('ordenes', [OrderServiceController::class, 'index'])->name('order');
Route::get('equipos', [EquipoController::class, 'index'])->name('equipos');
Route::get('ordenes/create', [OrderServiceController::class, 'create'])->name('order.create');
