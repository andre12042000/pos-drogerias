<?php

namespace Database\Seeders;

use App\Models\Presentacion;
use Illuminate\Database\Seeder;

class PresentacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Presentacion::create([
            'name'                  => 'N/A',
            'disponible_caja'       => false,
            'disponible_blister'    => false,
            'disponible_unidad'     => false,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);

        Presentacion::create([
            'name'                  => 'AMPOLLA',
            'disponible_caja'       => true,
            'disponible_blister'    => false,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);

        Presentacion::create([
            'name'                  => 'BLISTER',
            'disponible_caja'       => false,
            'disponible_blister'    => true,
            'disponible_unidad'     => false,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);

        Presentacion::create([
            'name'                  => 'BOLSA',
            'disponible_caja'       => false,
            'disponible_blister'    => false,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);

        Presentacion::create([
            'name'                  => 'BOTELLA',
            'disponible_caja'       => false,
            'disponible_blister'    => false,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);
        Presentacion::create([
            'name'                  => 'CAJA',
            'disponible_caja'       => true,
            'disponible_blister'    => false,
            'disponible_unidad'     => false,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);
        Presentacion::create([
            'name'                  => 'CAPSULA',
            'disponible_caja'       => true,
            'disponible_blister'    => true,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);
        Presentacion::create([
            'name'                  => 'DISPENSADOR',
            'disponible_caja'       => false,
            'disponible_blister'    => false,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);

        Presentacion::create([
            'name'                  => 'ESTUCHE',
            'disponible_caja'       => true,
            'disponible_blister'    => true,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);
        Presentacion::create([
            'name'                  => 'GOTAS',
            'disponible_caja'       => false,
            'disponible_blister'    => false,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);
        Presentacion::create([
            'name'                  => 'KIT',
            'disponible_caja'       => true,
            'disponible_blister'    => true,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);
        Presentacion::create([
            'name'                  => 'OFERTA',
            'disponible_caja'       => true,
            'disponible_blister'    => false,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);

        Presentacion::create([
            'name'                  => 'PAQUETE',
            'disponible_caja'       => true,
            'disponible_blister'    => false,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);
        Presentacion::create([
            'name'                  => 'POTE',
            'disponible_caja'       => false,
            'disponible_blister'    => false,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);
        Presentacion::create([
            'name'                  => 'PREPACK',
            'disponible_caja'       => false,
            'disponible_blister'    => false,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);
        Presentacion::create([
            'name'                  => 'SOBRES',
            'disponible_caja'       => true,
            'disponible_blister'    => false,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);
        Presentacion::create([
            'name'                  => 'TABLETAS',
            'disponible_caja'       => true,
            'disponible_blister'    => true,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);
        Presentacion::create([
            'name'                  => 'TARRO',
            'disponible_caja'       => false,
            'disponible_blister'    => false,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);
        Presentacion::create([
            'name'                  => 'TUBO',
            'disponible_caja'       => false,
            'disponible_blister'    => false,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);
        Presentacion::create([
            'name'                  => 'UNIDAD',
            'disponible_caja'       => false,
            'disponible_blister'    => false,
            'disponible_unidad'     => true,
            'por_caja'              => '30',
            'por_blister'           => '40',
            'por_unidad'            => '50',
            'status'                => 'ACTIVE'
        ]);

    }
}
