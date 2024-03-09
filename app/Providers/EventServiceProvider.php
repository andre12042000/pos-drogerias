<?php

namespace App\Providers;

use App\Events\ConsumoInternoRealizado;
use App\Events\NuevaVentaRegistrada;
use App\Events\VentaCreditoRealizada;
use App\Events\VentaRealizada;
use App\Listeners\CrearInventarioParaProductoNuevo;
use App\Listeners\DescontarInventario;
use App\Listeners\DescontarInventarioConsumoInterno;
use App\Listeners\DescontarInventarioNuevaVenta;
use App\Listeners\DescontarInventarioVentaCredito;
use App\Models\ConsumoInterno;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        VentaRealizada::class => [
            DescontarInventario::class,
        ],
        VentaCreditoRealizada::class => [
            DescontarInventarioVentaCredito::class,
        ],
        ConsumoInternoRealizado::class => [
            DescontarInventarioConsumoInterno::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
