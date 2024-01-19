<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea copia de seguridad de todo el sistema';

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
        Artisan::call('backup:run');

        $texto = date("Y-m-d H:i:s") . " - Backup realizado";

        Storage::append("archivo.txt", $texto);

        return true;
    }
}
