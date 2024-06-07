<?php

namespace App\Traits;

use App\Models\Empresa;
use App\Traits\ObtenerImpresora;

use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

use Illuminate\Support\Facades\Auth;

trait ImprimirTrait
{
    use ObtenerImpresora;
    public function imprimirRecibo($reciboBody, $cajero)
    {
        try {
            // Obtener la instancia de la empresa
            $empresa = Empresa::find(1);
            if (!$empresa) {
                throw new \Exception('No se encontró la empresa.');
            }

            // Obtener el nombre de la impresora
            $printerName = $this->obtenerImpresora();
            if (!$printerName) {
                throw new \Exception('No se encontró ninguna impresora disponible.');
            }

            // Conectar a la impresora
            $connector = new WindowsPrintConnector($printerName);
            $printer = new Printer($connector);

            // Cargar la imagen del recibo
            $rutaImagen = public_path('img/recibos.png');
            $escposResizedImg = EscposImage::load($rutaImagen);

            // Imprimir la imagen redimensionada
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->bitImage($escposResizedImg);
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text(strtoupper($empresa->name) . "\n");
            $printer->text("NIT: " . $empresa->nit . "-". $empresa->dv . "\n");
            $printer->text("Telefono: " . $empresa->telefono . "\n");
            $printer->text($empresa->email . "\n");
            $printer->text($empresa->direccion . "\n");
            $fechaHoraImpresion = date('Y-m-d H:i:s');
            $printer->text("Cierre: " . $fechaHoraImpresion . "\n");
            $printer->text("Imprime: " . Auth::user()->name . "\n");
            $printer->text("\n");
            $printer->text("\n");

            // Imprimir el cuerpo del recibo
            foreach ($reciboBody as $item) {

                $metodo = str_pad($item['label'], 16);

                $valor = str_pad($item['value'], 15, " ", STR_PAD_LEFT);
                $lineaImpresion = $metodo . $valor . "\n";

                $printer->text($lineaImpresion);

            }

            $printer->text("--------------------------------\n");
            $printer->text("Cajero: " . $cajero . "\n");

            $printer->cut();
            $printer->close();

        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert-error-imprimir');

        }
    }


}










