<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use Illuminate\Database\Seeder;

class MetodoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MetodoPago::create([
            'name'          => 'QR',
            'status'        => 'ACTIVE',
        ]);

        MetodoPago::create([
            'name'          => 'EFECTIVO',
            'status'        => 'ACTIVE',
        ]);
    }
}
