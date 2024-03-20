<?php


use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Product\ShowComponent;
use App\Http\Livewire\Product\LowStockComponent;

use App\Http\Controllers\Inventario\ProductController;
use App\Http\Controllers\Inventario\PurchaseController;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use App\Http\Controllers\Inventario\ImportProductsController;
use App\Http\Livewire\Product\CorreccionProductsComponent;
use App\Http\Livewire\Product\ComboCreateComponent;


Route::middleware([ PermissionMiddleware::class.':Acceso Inventario Ver'])->group(function () {

Route::get('productos', [ProductController::class, 'index'])->name('product');

Route::get('productos/stock/min', [ProductController::class, 'low'])->name('stock.min');
Route::get('low_stock', LowStockComponent::class)->name('low.stock');
Route::get('productos/detalles/{product}', ShowComponent::class)->name('product.show');

Route::get('compras', [PurchaseController::class, 'index'])->name('purchase');
Route::get('compras/detalles/{purchase}', [PurchaseController::class, 'edit'])->name('purchase.edit')->middleware([ PermissionMiddleware::class.':Acceso Inventario Editar']);

Route::get('productos/import',  [ImportProductsController::class, 'importview'])->name('import.view');
Route::post('productos/import/data',  [ImportProductsController::class, 'importardata'])->name('import.data');

Route::get('productos/importar', [ImportProductsController::class, 'importar'])->name('importador');

Route::get('productos/correccion', [CorreccionProductsComponent::class, '__invoke'])->name('correccion');

Route::get('combo/create', [ComboCreateComponent::class, '__invoke'])->name('combo.create');

});




