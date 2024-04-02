<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Terceros\ClientController;
use App\Http\Livewire\Client\DetailComponent;
use App\Http\Controllers\Terceros\ProviderController;
use Spatie\Permission\Middlewares\PermissionMiddleware;

Route::middleware([ PermissionMiddleware::class.':Acceso Terceros'])->group(function () {
    Route::middleware([ PermissionMiddleware::class.':Acceso Clientes'])->group(function () {
    Route::get('clientes', [ClientController::class, 'index'])->name('client');
    Route::get('clientes/detalles/{cliente_id}', [DetailComponent::class, '__invoke'])->name('client.details');
});
Route::middleware([ PermissionMiddleware::class.':Acceso Proveedores'])->group(function () {

    Route::get('proveedores', [ProviderController::class, 'index'])->name('provider');
});
});
