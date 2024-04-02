<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sale\SaleController;
use App\Http\Livewire\Sale\SaleCafeteriaComponent;
use Spatie\Permission\Middlewares\PermissionMiddleware;

Route::middleware([ PermissionMiddleware::class.':Acceso Pos'])->group(function () {

Route::get('pos', [SaleController::class, 'index'])->name('pos');
Route::get('detalles/{venta}', [SaleController::class, 'show'])->name('pos.details');
Route::get('generarpdf/{venta}', [SaleController::class, 'generarpdf'])->name('pos.pdf');

Route::get('imprimir/{venta}', [SaleController::class, 'imprimirrecibo'])->name('pos.imprimir.recibo');
});
Route::middleware([ PermissionMiddleware::class.':Acceso Venta Mesa'])->group(function () {


Route::get('restaurant', [SaleCafeteriaComponent::class, '__invoke'])->name('restaurant');
});

