<?php

namespace App\Http\Livewire\ConsumoInterno;

use App\Events\ConsumoInternoRealizado;
use App\Models\Cash;
use App\Models\ConsumoInterno;
use App\Models\ConsumoInternoDetalles;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateComponent extends Component
{
    protected $listeners = ['guardarConsumoInternoEvent' => 'save'];

    public $selectedProducts = [];
    public $totalTransaccion = 0;

    public $buscar;
    public function render()
    {
        $products = Product::with('inventario')
            ->search($this->buscar)
            ->orderBy('name', 'asc')
            ->active()
            ->take(5)
            ->get();


        return view('livewire.consumo-interno.create-component', compact('products'))->extends('adminlte::page');
    }

    public function save($data)
    {

        if (empty($data['selectedProducts']) || $data['totalTransaccion'] == 0) {
            // Puedes agregar lógica de manejo de errores aquí
            return false;
        }

        try {

            DB::transaction(function () use ($data) {


                $prefijo = 'CIN';
                $nuevoNro = self::obtenerProximoNumero($prefijo);
                $full_nro = $prefijo . $nuevoNro;


                $transaccion = ConsumoInterno::create([
                    'prefijo'       => $prefijo,
                    'nro'           => $nuevoNro,
                    'full_nro'      => $full_nro,
                    'user_id'       =>  Auth::user()->id,
                    'total'         =>  $data['totalTransaccion'],
                    'status'        => 'APLICADA',
                ]);

                self::guardarDetalles($transaccion, $data['selectedProducts']);

                self::crearRegistroCash($transaccion);

                event(new ConsumoInternoRealizado($transaccion));

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

    function crearRegistroCash($transaccion)
    {
        Cash::create([
            'user_id'           => Auth::user()->id,
            'cashesable_id'     => $transaccion->id,
            'cashesable_type'   => 'App\Models\ConsumoInterno',
            'quantity'          => $transaccion->total,
        ]);

    }

    function guardarDetalles($transaccion, $detalles)
    {
        foreach ($detalles as $detalle) {
            ConsumoInternoDetalles::create([
                'consumo_interno_id'     => $transaccion->id,
                'product_id'             => $detalle['product_id'],
                'forma'                  => $detalle['forma'],
                'quantity'               => $detalle['cantidad'],
                'price'                  => $detalle['precio_unitario'],
            ]);
        }
    }

    public static function obtenerProximoNumero($prefijo)
    {

        $ultimoRecibo = ConsumoInterno::where('prefijo', $prefijo)
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
