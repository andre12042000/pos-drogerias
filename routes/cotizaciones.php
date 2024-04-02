<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImprimirController;
use Spatie\Permission\Middlewares\PermissionMiddleware;



Route::middleware([ PermissionMiddleware::class.':Acceso Cotizaciones'])->group(function () {

Route::get('/list', [\App\Http\Livewire\Cotizaciones\ListComponent::class, '__invoke'])->middleware('auth')->name('cotizaciones.list');
Route::get('/crear', [\App\Http\Livewire\Cotizaciones\CreateComponent::class, '__invoke'])->middleware('auth')->name('cotizaciones.create');
Route::get('imprimir/{transaccion_id}', [ImprimirController::class, 'cotizacionpdf'])->name('generarpdfcotizacion');
});
