<?php

namespace App\Http\Controllers\Sale;

use App\Traits\ObtenerImpresora;
use Dompdf\Dompdf;
use App\Models\Sale;
use App\Models\Empresa;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PagoCreditos;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class SaleController extends Controller
{
    use ObtenerImpresora;


    public function index()
    {
        $empresa = Empresa::find(1);

        return view('ventas.pos.index', compact('empresa'));
    }

    public function show($sale)
    {

        $venta = $sale;
        $sales = Sale::find($sale);
        $empresa = Empresa::find(1);
        $detailsales = SaleDetail::where('sale_id', $sale)->get();


        return view('ventas.pos.detalles', compact('sales', 'empresa', 'detailsales', 'venta'));
    }



    public function generarpdf($id)
    {


        $sales = Sale::find($id);
        $empresa = Empresa::find(1);
        $detailsales = SaleDetail::where('sale_id', $id)->get();
        $dompdf = new Dompdf();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('enable_html5_parser', true);
        $dompdf->set_option('enable_remote', true);

        // Cargar la vista que deseas convertir en PDF
        $view = view('ventas.pos.exportar', compact('sales', 'empresa', 'detailsales'))->render();

        // Renderizar la vista en un archivo PDF
        $dompdf->loadHtml($view);
        $dompdf->render();

        // Devolver el PDF como respuesta
        return $dompdf->stream('archivo.pdf');
    }

    public function imprimirrecibo($id)
    {

        try {
            $printerName =  $this->obtenerimpresora();

            if(!$printerName) {
                throw new \Exception('No se encontró ninguna impresora disponible.');
            }

            $sales = Sale::find($id);
            $empresa = Empresa::find(1);
            $detailsales = SaleDetail::where('sale_id', $id)->get();

            if ($sales->client->number_document) {
                $numeoridentidad = $sales->client->number_document;
            } else {
                $numeoridentidad = 'No disponible';
            }

/*             $fecha = \Carbon\Carbon::parse($sales->created_at)->locale('es_ES')->formatLocalized('%d %B %Y ');
 */            $fecha = \Carbon\Carbon::parse($sales->created_at)->format('d M Y');

            $connector = new WindowsPrintConnector($printerName);
            $printer = new Printer($connector);

            $rutaImagen = public_path('img\recibos.png');
            $escposResizedImg = EscposImage::load($rutaImagen);

            // Imprime la imagen redimensionada
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->bitImage($escposResizedImg);
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text(strtoupper($empresa->name) . "\n");
            $printer->text("NIT: " . $empresa->nit . "-".$empresa->dv . "\n");
            $printer->text("Telefono: " . $empresa->telefono . "\n");
            $printer->text($empresa->email . "\n");
            $printer->text($empresa->direccion . "\n");
            $printer->text("\n");
            $printer->text("Recibo: " . $sales->full_nro . "\n");
            $printer->text("Cliente: " . $sales->client->name . "\n");
            $printer->text("Identificacion: " . $numeoridentidad . "\n");
            $printer->text("Metodo de pago: " . $sales->metodopago->name . "\n");
            $printer->text("Fecha: " . $fecha . "\n");
            $printer->text("Cajero: " . $sales->user->name . "\n");
            $printer->text("\n");

            $printer->text("Detalles de pago\n");
            $printer->text("--------------------------------\n");
            $printer->text(" Producto        Cant   Subtotal\n");
            $printer->text("--------------------------------\n");
            foreach ($detailsales as $detalle) {
                $palabras = Str::of($detalle->product->name)->explode(' ');
                $nombreConcepto = '';

                // Verifica si hay 3 o más palabras
                if (count($palabras) >= 2) {
                    $nuevasPalabras = [];
                    foreach ($palabras as $palabra) {
                        $nuevasPalabras[] = Str::limit($palabra, 20, '');
                    }
                    $nombreConcepto = implode(' ', $nuevasPalabras);
                } else {
                    $nombreConcepto = $detalle->product->name;
                }

                if (strlen($nombreConcepto) > 11) {

                    $nombreConcepto = substr($nombreConcepto, 0, 11);
                    $nombreConcepto = $nombreConcepto . '-';
                }

                $nombreConcepto = str_pad($nombreConcepto, 16); // Ajusta el ancho según tus necesidades

                $totalRecaudo = number_format($detalle->price *  $detalle->quantity, 0); // Quita los decimales
                $totalRecaudo = str_pad($totalRecaudo, 11, " ", STR_PAD_LEFT);

                $cant = $detalle->quantity;
                $cantidad = str_pad($cant, 5, " ", STR_PAD_LEFT);

                $lineaImpresion = $nombreConcepto . $cantidad . $totalRecaudo . "\n";

                $printer->text($lineaImpresion);
            }

            $printer->text("--------------------------------\n");
            $printer->text("\n");
            $printer->text(iconv("UTF-8", "CP437", "Total: " . number_format($sales->total, 0) . "\n"));
            $printer->text("\n");
            $printer->text("Gracias por tu compra! \n");
            $printer->text("\n");
            $printer->text("\n");
            $printer->pulse();
            $printer->cut();
            $printer->close();
        } catch (\Exception $e) {
            // Aquí puedes manejar el error como desees, por ejemplo, mostrar un mensaje al usuario
            echo "Error: " . $e->getMessage();
        }

        return redirect()->back();
    }


    function quitarTildes($string)
    {
        $map = [
            'á' => 'a', 'Á' => 'A',
            'é' => 'e', 'É' => 'E',
            'í' => 'i', 'Í' => 'I',
            'ó' => 'o', 'Ó' => 'O',
            'ú' => 'u', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N',
        ];

        return strtr($string, $map);
    }
}
