<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Darwin Montoya',
            'email' => 'ing@darwinmontoya.com',
            'password' => bcrypt('MONtoSHA21'),
            'status'   => 'ACTIVO',
        ]);
        $user->assignRole('Administrador');


       /*   $user= User::create([
            'name' => 'ADMINISTRADOR',
            'email' => 'admin-pos-venta@gmail.com',
            'password' => bcrypt('Admin2023OPTICA'),
            'status'    => 'ACTIVO',
        ]);
        $user->assignRole('Administrador'); */
    }
}
