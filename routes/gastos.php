<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Gastos\ListComponent;
use App\Http\Livewire\Gastos\EditGastoComponent;
use Spatie\Permission\Middlewares\PermissionMiddleware;


Route::middleware([ PermissionMiddleware::class.':Acceso Gastos'])->group(function () {

    Route::get('/', [ListComponent::class, '__invoke'])->name('list');
    Route::get('edit/{gasto_id}', [EditGastoComponent::class, '__invoke'])->name('edit');
});
