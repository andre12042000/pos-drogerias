<?php

namespace Database\Seeders;

use App\Models\Brand;

use App\Models\Client;
use App\Models\Product;
use App\Models\Category;

use App\Models\Provider;
use App\Models\Laboratorio;
use App\Models\Presentacion;
use App\Models\Subcategoria;
use App\Models\Ubicacion;
use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;
use Modules\Mantenimiento\Entities\TipoEquipo;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EmpresaSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);


        UnidadMedida::create([
            'name'      => 'N/A'
        ]);

        UnidadMedida::create([
            'name'      => 'CAJA'
        ]);

        UnidadMedida::create([
            'name'      => 'GOTAS'
        ]);

        UnidadMedida::create([
            'name'      => 'UNIDAD'
        ]);

        UnidadMedida::create([
            'name'      => 'AMPOLLA'
        ]);

        UnidadMedida::create([
            'name'      => 'BOLSA'
        ]);

        UnidadMedida::create([
            'name'      => 'PAQUETE'
        ]);

        UnidadMedida::create([
            'name'      => 'OFERTA'
        ]);

        UnidadMedida::create([
            'name'      => 'FRASCO'
        ]);

        UnidadMedida::create([
            'name'      => 'TARRO'
        ]);

        UnidadMedida::create([
            'name'      => 'TUBO'
        ]);

        UnidadMedida::create([
            'name'      => 'POTE'
        ]);

        UnidadMedida::create([
            'name'      => 'SOBRES'
        ]);

        UnidadMedida::create([
            'name'      => 'TABLETAS'
        ]);

        UnidadMedida::create([
            'name'      => 'CAPSULA'
        ]);

        UnidadMedida::create([
            'name'      => 'DISPENSADOR'
        ]);

        UnidadMedida::create([
            'name'      => 'CA'
        ]);

        UnidadMedida::create([
            'name'      => 'KIT'
        ]);

        UnidadMedida::create([
            'name'      => 'BOTELLA'
        ]);

        UnidadMedida::create([
            'name'      => 'ESTUCHE'
        ]);

        UnidadMedida::create([
            'name'      => 'DOC'
        ]);

        UnidadMedida::create([
            'name'      => 'BLISTER'
        ]);
        UnidadMedida::create([
            'name'      => 'BTO'
        ]);


        Laboratorio::create([
            'name'      => 'N/A',
            'status'    => 'ACTIVE'
        ]);

        //Marcas

        Brand::create([
            'name'      => 'N/A',
        ]);

        Ubicacion::create([
            'name'      => 'N/A',
            'status'    => 'ACTIVE'
        ]);

      /*   Brand::create([
            'name'      => 'Suzuki',
        ]);

        Brand::create([
            'name'      => 'Yamaha',
        ]); */

        //Categorias

        Category::create([
            'name'      => 'N/A',
        ]);

      /*   Category::create([
            'name'      => 'Servicios',
        ]); */

      /*   Category::create([
            'name'      => 'Salud',
        ]); */

        //Cliente

        Client::create([
            'type_document'     => null,
            'number_document'   => null,
            'name'              => 'Venta rapida',
            'phone'             => null,
            'address'           => null,
            'email'             => null,
        ]);

        //Proveedor

        Provider::create([
            'nit'               => null,
            'name'              => 'N/A',
            'phone'             => null,
            'address'           => null,
            'email'             => null,
        ]);
        Presentacion::create([
            'name'      => 'N/A',
            'status'    => 'ACTIVE'
        ]);

        Subcategoria::create([
            'name'          => 'N/A',
            'status'        => 'ACTIVE',
            'category_id'   => 1,
        ]);


     /*    Product::factory(1000)->create(); */
   /*   Product::create([
        'code'                      => '00001',
        'name'                      => 'Servicio',
        'stock'                     => '1',
        'stock_min'                 => '1',
        'stock_max'                 => '1',
        'sell_price'                => '0',
        'sell_price_tecnico'        => '0',
        'sell_price_distribuidor'   => '0',
        'status'                    => 'ACTIVE',
        'category_id'               => 1,
        'medida_id'                 => 1,
    ]);


     TipoEquipo::create([
        'descripcion'      => 'Monturas',
    ]);
    TipoEquipo::create([
        'descripcion'      => 'Lentes',
    ]);
    TipoEquipo::create([
        'descripcion'      => 'Gafas',
    ]);
*/
    }
}
