<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ImpresoraController;
use App\Http\Controllers\Parametros\CategoryController;
use App\Http\Livewire\Notification\NotificationComponent;
use App\Http\Controllers\NombreDeTuControlador;
use App\Models\Product;
use App\Models\PurchaseDetail;
use App\Models\SaleDetail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    } else {
        return redirect('/login');
    }
});


Auth::routes([
    'register' => false
]);

Route::get('eliminar', [CategoryController::class, 'destroy'])->middleware('auth', 'change.password')->name('destroycategory');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth', 'change.password')->name('home');

Route::get('notificaciones/', NotificationComponent::class)->middleware('auth')->name('notificaciones.index');
Route::get('notifications/get', [App\Http\Controllers\NotificationsController::class, 'getNotificationsData'])->middleware('auth', 'change.password')->name('notifications.get');

Route::post('/mark-as-read', [App\Http\Controllers\NotificationsController::class, 'markNotification'])->middleware('auth', 'change.password')->name('markNotification');

Route::get('markAsRead', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return redirect()->back();
})->middleware('auth', 'change.password')->name('markAsRead');
Route::get('perfil', [PerfilController::class, 'index'])->middleware('auth')->name('perfil');



Route::get('/obtener-informacion-cliente', [NombreDeTuControlador::class, 'obtenerInformacionCliente']);

/* Route::get('eliminar_registros', function () {
    $productos = Product::whereColumn('created_at', '=', 'updated_at')->with('inventario')->get();
    foreach ($productos as $producto) {

        if ($producto->inventario->cantidad_caja == 0 && $producto->inventario->cantidad_blister == 0 && $producto->inventario->cantidad_unidad == 0) {

            // Almacenar el producto en el array antes de eliminarlo

            $sale = SaleDetail::where('product_id', $producto->id)->first();
            $purchase = PurchaseDetail::where('product_id', $producto->id)->first();

            if (!$sale AND !$purchase) {

                $productosEliminados[] = $producto->code . ' - ' . $producto->name;

                // Eliminar el inventario primero
                $producto->inventario->delete();

                // Luego, eliminar el producto
                $producto->delete();
            }
        }
    }

    return $productosEliminados;
}); */



