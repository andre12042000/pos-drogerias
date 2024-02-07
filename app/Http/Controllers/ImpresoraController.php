<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Impresora;
use App\Traits\ObtenerImpresora;
use Illuminate\Support\Str;


use Illuminate\Http\Request;


use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrinterConnectors\FilePrintConnector;





class ImpresoraController extends Controller
{
    use ObtenerImpresora;
    public function index()
    {
        $impresoras = Impresora::all();
        return view('setting.impresoras.index', compact('impresoras'));
    }

    public function cancel()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    public function obtenerInformacionCliente(Request $request)
    {
        $ip = $request->ip();
        $hostname = gethostbyaddr($ip);
dd($ip);
        return $ip;

    }

      public function pruebaimprimirrecibo(Request $request){

        $this->obtenerInformacionCliente($request);
        $empresa = Empresa::find(1);

        $impresora =  $this->obtenerimpresora();

        $printerName = $impresora;

        try {
            $connector = new WindowsPrintConnector($printerName);
            $printer = new Printer($connector);

            $rutaImagen = public_path('img\recibos.png');
            $escposResizedImg = EscposImage::load($rutaImagen);

            // Imprime la imagen redimensionada
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->bitImage($escposResizedImg);
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("NIT: " . $empresa->nit . "\n");
            $printer->text("Telefono: " . $empresa->telefono . "\n");
            $printer->text($empresa->email . "\n");
            $printer->text($empresa->direccion . "\n");
            $printer->text("\n");
            $printer->text("--------------------------------\n");
            $printer->text("\n");

            $printer->text("Datos del recibo - informe\n");
            $printer->text("--------------------------------\n");
            $printer->text("Gracias por tu compra! \n");
            $printer->text("\n");
            $printer->cut();
            $printer->close();
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return redirect()->route('impresoras')->with('message', 'Empresa actualizada exitosamente');

    }
}
