<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sale\SaleController;
use App\Http\Livewire\Gastos\ListComponent;
use Spatie\Permission\Middlewares\PermissionMiddleware;

Route::middleware([ PermissionMiddleware::class.':Acceso Pos Venta'])->group(function () {

Route::get('pos', [SaleController::class, 'index'])->name('pos');
Route::get('detalles/{venta}', [SaleController::class, 'show'])->name('pos.details');
Route::get('generarpdf/{venta}', [SaleController::class, 'generarpdf'])->name('pos.pdf');

Route::get('imprimir/{venta}', [SaleController::class, 'imprimirrecibo'])->name('pos.imprimir.recibo');
Route::get('gastos/list', [ListComponent::class, '__invoke'])->middleware('auth')->name('gastos.list');
Route::get('cotizaciones/list', [\App\Http\Livewire\Cotizaciones\ListComponent::class, '__invoke'])->middleware('auth')->name('cotizaciones.list');
Route::get('cotizaciones/crear', [\App\Http\Livewire\Cotizaciones\CreateComponent::class, '__invoke'])->middleware('auth')->name('cotizaciones.create');

});
