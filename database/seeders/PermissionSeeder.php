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
            'name' => 'Acceso Movimientos',
        ]);

        Permission::create([
            'name' => 'Acceso Pos',
        ]);

        Permission::create([
            'name' => 'Acceso Venta Mesa',
        ]);

        Permission::create([
            'name' => 'Acceso Cotizaciones',
        ]);

        Permission::create([
            'name' => 'Acceso Consumo Interno',
        ]);

        Permission::create([
            'name' => 'Acceso Gastos',
        ]);

        Permission::create([
            'name' => 'Acceso Ordenes Trabajo',
        ]);

        Permission::create([
            'name' => 'Acceso Facturacion',
        ]);



        Permission::create([
            'name' => 'Acceso Inventario',
        ]);

        Permission::create([
            'name' => 'Acceso Compras',
        ]);

        Permission::create([
            'name' => 'Acceso Producto',
        ]);
        Permission::create([
            'name' => 'Acceso Producto Crear',
        ]);
        Permission::create([
            'name' => 'Acceso Producto Editar',
        ]);
        Permission::create([
            'name' => 'Acceso Producto Ajustar',
        ]);

        Permission::create([
            'name' => 'Acceso Producto Stock Bajo',
        ]);

        Permission::create([
            'name' => 'Acceso Terceros',
        ]);

        Permission::create([
            'name' => 'Acceso Clientes',
        ]);

        Permission::create([
            'name' => 'Acceso Proveedores',
        ]);

        Permission::create([
            'name' => 'Acceso Parametros',
        ]);





        Permission::create([
            'name' => 'Acceso Seguridad',
        ]);

        Permission::create([
            'name' => 'Acceso Usuario',
        ]);
        Permission::create([
            'name' => 'Acceso Roles',
        ]);
        Permission::create([
            'name' => 'Acceso Configuración',
        ]);




//posibles

Permission::create([
    'name' => 'Acceso Mantenimiento',
]);

Permission::create([
    'name' => 'Acceso Equipo',
]);



    }
}
