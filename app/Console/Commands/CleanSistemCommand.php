<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class CleanSistemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:sistem';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia temporales del sistema';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        Artisan::call('optimize:clear');

        $texto = date("Y-m-d H:i:s") . " - Limpieza realizada";

        Storage::append("archivo.txt", $texto);



        return true;
    }
}
