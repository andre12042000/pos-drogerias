<?php

namespace App\Http\Livewire\Cotizaciones;

use Carbon\Carbon;
use App\Models\Cash;
use App\Models\Client;
use App\Models\Product;
use Livewire\Component;
use App\Models\Cotizacion;
use App\Models\ConsumoInterno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ConsumoInternoDetalles;
use App\Events\ConsumoInternoRealizado;
use App\Models\CotizacionDetalle;

class CreateComponent extends Component
{
    protected $listeners = ['guardarCotizacionEvent' => 'save'];

    public $selectedProducts = [];
    public $totalTransaccion = 0;
    public $ivaTransaccion = 0;
    public $subtotalTransaccion = 0;



    public $buscar;
    public function render()
    {
        $products = Product::with('inventario')
            ->search($this->buscar)
            ->orderBy('name', 'asc')
            ->active()
            ->take(10)
            ->get();

        $clientes = Client::all();
        return view('livewire.cotizaciones.create-component', compact('products', 'clientes'))->extends('adminlte::page');
    }

    public function save($data)
    {

        if (empty($data['selectedProducts']) || $data['totalTransaccion'] == 0) {
            // Puedes agregar lógica de manejo de errores aquí
            return false;
        }
        try {

            DB::transaction(function () use ($data) {
                $hoy = Carbon::now();
                $hoy = $hoy->format('Y-m-d');

                $prefijo = 'CTZ';
                $nuevoNro = self::obtenerProximoNumero($prefijo);
                $full_nro = $prefijo . $nuevoNro;


                $transaccion = Cotizacion::create([
                    'prefijo'            => $prefijo,
                    'nro'                => $nuevoNro,
                    'full_nro'           => $full_nro,
                    'user_id'            =>  Auth::user()->id,
                    'client_id'         =>  $data['clienteSeleccionado'],
                    'cotizacion_date'    =>  $hoy,
                    'discount'           =>  $data['descuentoTototal'],
                    'tax'                =>  $data['ivaTransaccion'],
                    'total'              =>  $data['totalTransaccion'],
                ]);



                self::guardarDetalles($transaccion, $data['selectedProducts']);

                $this->dispatchBrowserEvent('transaccion-generada', ['transaccion' => $transaccion->full_nro]);
            });
        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Ops! Ocurrio un error',
                'text' => '¡No es posible crear la transacción, verifica los datos!' . $e,
                'icon' => 'error'
            ]);

            report($e);
        }
    }

    function guardarDetalles($transaccion, $detalles)
    {
$descuento = '0.0';
        foreach ($detalles as $detalle) {
            CotizacionDetalle::create([
                'cotizacion_id'         => $transaccion->id,
                'product_id'             => $detalle['product_id'],
                'forma'                  => $detalle['forma'],
                'quantity'               => $detalle['cantidad'],
                'price'                  => $detalle['precio_unitario'],
                'tax'                    => $detalle['iva'],
                'discount'               => $descuento,
            ]);
        }
    }

    public static function obtenerProximoNumero($prefijo)
    {

        $ultimoRecibo = Cotizacion::where('prefijo', $prefijo)
            ->orderBy('nro', 'desc')
            ->first();

        if ($ultimoRecibo) {
            // Obtener el número actual y sumar 1
            $numero = (int) $ultimoRecibo->nro + 1;
        } else {
            // Si no hay registros previos, iniciar desde 1
            $numero = 1;
        }

        // Formatear el número con ceros a la izquierda (ej. 001, 002, ..., 199, 200, ...)
        $numeroFormateado = str_pad($numero, 3, '0', STR_PAD_LEFT);

        // Combinar el prefijo y el número formateado

        return $numeroFormateado;
    }
}
