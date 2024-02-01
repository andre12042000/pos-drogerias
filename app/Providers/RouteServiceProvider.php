<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web', 'auth', 'change.password')
                 ->name('parametros.')
                 ->prefix('parametros')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/parametros.php'));

            Route::middleware('web', 'auth', 'change.password')
                 ->name('terceros.')
                 ->prefix('terceros')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/terceros.php'));

            Route::middleware('web', 'auth', 'change.password')
                 ->name('inventarios.')
                 ->prefix('inventarios')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/inventario.php'));

            Route::middleware('web', 'auth', 'change.password')
                 ->name('ventas.')
                 ->prefix('ventas')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/ventas.php'));

                 Route::middleware('web', 'auth', 'change.password')
                 ->prefix('seguridad')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/security.php'));

                 Route::middleware('web', 'auth', 'change.password')
                 ->prefix('configuracion')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/configuracion.php'));

                 Route::middleware('web', 'auth', 'change.password')
                 ->prefix('reportes')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/reportes.php'));

                 Route::middleware('web', 'auth', 'change.password')
                 ->name('orders.')
                 ->prefix('orders')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/orders.php'));

                 Route::middleware('web', 'auth', 'change.password')
                 ->name('mantenimiento.')
                 ->prefix('mantenimiento')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/mantenimiento.php'));

                 Route::middleware('web', 'auth', 'change.password')
                 ->name('facturacion.')
                 ->prefix('facturacion')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/facturacion.php'));

            Route::middleware('web', 'auth', 'change.password')
            ->name('servicio.')
            ->prefix('servicio')
            ->namespace($this->namespace)
            ->group(base_path('routes/services.php'));

        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);
        });
    }
}
