<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdersController;
use App\Http\Livewire\Orders\EditComponent;
use Spatie\Permission\Middlewares\PermissionMiddleware;

Route::middleware([ PermissionMiddleware::class.':Acceso Ordenes Trabajo'])->group(function () {

Route::get('/', [OrdersController::class, 'index'])->name('index');
Route::get('/create', [OrdersController::class, 'create'])->name('create');
Route::get('/detalles/{order}', [OrdersController::class, 'show'])->name('show');
Route::get('edit/{order}', EditComponent::class)->name('edit');

});
