<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        Permission::create([
            'name' => 'Acceso Reportes',
        ]);

        Permission::create([
            'name' => 'Acceso Pos Venta',
        ]);

        Permission::create([
            'name' => 'Acceso Inventario Ver',
        ]);
        Permission::create([
            'name' => 'Acceso Inventraio Crear',
        ]);
        Permission::create([
            'name' => 'Acceso Inventario Editar',
        ]);
        Permission::create([
            'name' => 'Acceso Inventario Corregir',
        ]);

        Permission::create([
            'name' => 'Acceso Gestion Terceros',
        ]);

        Permission::create([
            'name' => 'Acceso Gestion Parametros',
        ]);

        Permission::create([
            'name' => 'Acceso Gestion Usuario',
        ]);
        Permission::create([
            'name' => 'Acceso Gestion Roles',
        ]);
        Permission::create([
            'name' => 'Acceso Configuración',
        ]);

        Permission::create([
            'name' => 'Acceso Mantenimiento',
        ]);


        Permission::create([
            'name' => 'Ordenes de trabajo',
        ]);

        Permission::create([
            'name' => 'Acceso Gestion Facturacion',
        ]);



    }
}
