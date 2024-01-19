<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;


class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Empresa::create([
            'name'      =>  'Nueva IPS Optica Del Oriente',
            'nit'       =>  '900547922',
            'dv'        =>  '9',
            'telefono'  =>  '3103597855',
            'email'     =>  'nuevaipsopticadeloriente@hotmail.com',
            'direccion' =>  'Calle 9 No 20-18, B. Centro',
        ]);
    }
}
