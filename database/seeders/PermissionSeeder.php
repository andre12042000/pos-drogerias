<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::query()->delete();
        $table = 'permissions';
        $resetAutoIncrementQuery = "ALTER TABLE $table AUTO_INCREMENT = 1;";
        DB::statement($resetAutoIncrementQuery);
        Permission::updateOrCreate([
            'name' => 'Acceso Reportes',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Movimientos',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Pos',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Venta Mesa',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Cotizaciones',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Consumo Interno',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Gastos',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Ordenes Trabajo',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Facturacion',
        ]);



        Permission::updateOrCreate([
            'name' => 'Acceso Inventario',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Compras',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Producto',
        ]);
        Permission::updateOrCreate([
            'name' => 'Acceso Producto Crear',
        ]);
        Permission::updateOrCreate([
            'name' => 'Acceso Producto Editar',
        ]);
        Permission::updateOrCreate([
            'name' => 'Acceso Producto Ajustar',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Producto Stock Bajo',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Terceros',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Clientes',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Proveedores',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Parametros',
        ]);





        Permission::updateOrCreate([
            'name' => 'Acceso Seguridad',
        ]);

        Permission::updateOrCreate([
            'name' => 'Acceso Usuario',
        ]);
        Permission::updateOrCreate([
            'name' => 'Acceso Roles',
        ]);
        Permission::updateOrCreate([
            'name' => 'Acceso Configuración',
        ]);




//posibles

Permission::updateOrCreate([
    'name' => 'Acceso Mantenimiento',
]);

Permission::updateOrCreate([
    'name' => 'Acceso Equipo',
]);



    }
}
