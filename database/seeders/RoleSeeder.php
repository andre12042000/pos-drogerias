<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        Role::query()->delete();
        $table = 'roles';
        $resetAutoIncrementQuery = "ALTER TABLE $table AUTO_INCREMENT = 1;";
        DB::statement($resetAutoIncrementQuery);

        $role =  Role::create(['name' => 'Administrador']);
        $role->permissions()->attach([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26]);

        $role = Role::create(['name' => 'Cajero']);
        $role->permissions()->attach([4,5]);

        $role = Role::create(['name' => 'Asistente']);
        $role->permissions()->attach([]);
    }
}
