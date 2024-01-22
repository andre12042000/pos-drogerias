<?php

namespace App\Providers;

use App\Models\Empresa;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

       /*  Carbon::setlocale('es');
        setlocale(LC_TIME, 'es_ES'); */

   /*      Carbon::setUTF8(true);
        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_CO', 'es', 'ES', 'es_MX.utf8'); */

    }
}
