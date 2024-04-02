<?php


use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use App\Http\Livewire\Facturacion\ListadoComponent;
use App\Http\Livewire\Facturacion\EditComponent;
use App\Http\Livewire\Facturacion\AnularFacturaComponent;


//Route::get('/', [ListadoComponent::class, '__invoke'])->name('index');

 Route::middleware([ PermissionMiddleware::class.':Acceso Facturacion'])->group(function () {



    Route::get('/', [ListadoComponent::class, '__invoke'])->name('index');
    Route::get('edit/{sale_id}', [EditComponent::class, '__invoke'])->name('edit');
    Route::get('anular/{sale_id}', [AnularFacturaComponent::class, '__invoke'])->name('anular');
});
