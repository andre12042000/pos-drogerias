<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role =  Role::create(['name' => 'Administrador']);
        $role->permissions()->attach([1,2,3,4,5,6,7,8,9,10,11]);

        $role = Role::create(['name' => 'Cajero']);
        $role->permissions()->attach([2,3]);

        $role = Role::create(['name' => 'Asistente']);
        $role->permissions()->attach([]);
    }
}
