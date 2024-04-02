<?php

namespace App\Http\Livewire\Product;

use App\Models\Combo;
use Dotenv\Validator;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class ComboCreateComponent extends Component
{
    use WithFileUploads;
    protected $listeners = ['agregarProductoCrearCombo' => 'addProduct'];
    public $productosCrearCombo = [];
    public $costo_total = 0;
    public $precio_sugerido = 0;
    public $iva = 0;

    public $codigo, $nombre, $precio_venta, $imagen;
    public $error_cantidad_productos = false;

    protected $rules = [
        'nombre'            => 'required|min:3|max:255',
        'codigo'            => 'required|min:3|max:255|unique:products,code',
        'costo_total'       => 'required',
        'precio_venta'      => 'required|min:0'
    ];


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    public function render()
    {
        return view('livewire.product.combo-create-component')->extends('adminlte::page');
    }

    public function addProduct($codigo, $cantidad, $valorSeleccionadoForma)
    {
        $producto = self::obtenerDetalleProducto($codigo);

        if($valorSeleccionadoForma == 'disponible_caja'){
            $costo_unitario = $producto->costo_caja;
            $forma = 'Caja';
        }elseif($valorSeleccionadoForma == 'disponible_blister'){
            $costo_unitario = $producto->costo_blister;
            $forma = 'Blister';
        }else{
            $costo_unitario = $producto->costo_unidad;
            $forma = 'Unidad';
        }

        $total = $costo_unitario * $cantidad;


        $this->productosCrearCombo[] = [
            'producto_id'           => $producto->id,
            'name'                  => $producto->name,
            'codigo'                => $codigo,
            'forma'                 => $valorSeleccionadoForma,
            'forma_presentacion'    => $forma,
            'cantidad'              => $cantidad,
            'costo_unitario'        => $costo_unitario,
            'total'                 => $total
        ];

        self::calcularCostoTotal();
    }

    function calcularCostoTotal()
    {
        $this->costo_total = 0;
        $this->precio_sugerido = 0;

        foreach($this->productosCrearCombo as $producto){
            $this->costo_total += round($producto['total']);
        }

        $this->precio_sugerido = round((($this->costo_total * 30)/100) + $this->costo_total);


    }

    function obtenerDetalleProducto($codigo)
    {
        $producto = Product::where('code', $codigo)->first();

        return $producto;

    }

    public function eliminarProducto($index)
    {
        // Verifica si el índice existe en el array
        if (isset($this->productosCrearCombo[$index])) {
            // Elimina el elemento del array en el índice dado
            array_splice($this->productosCrearCombo, $index, 1);
        }
    }

    public function save()
    {
        $this->validate();
        $photo = $this->imagen;

        if(isset($photo) && $photo instanceof \Illuminate\Http\UploadedFile){
            $this->imagen = $photo->store('livewire-tem');
        } else {
            $this->imagen = null;
        }

        if (count($this->productosCrearCombo) < 2) {
            $this->error_cantidad_productos = true;
        }else{
            $this->error_cantidad_productos = false;
        }

        $producto = Product::create([
            'code'                          => $this->codigo,
            'name'                          => $this->nombre,
            'status'                        => 'ACTIVE',
            'iva_product'                   => $this->iva,
            'valor_iva_caja'                => 0,
            'valor_iva_blister'             => 0,
            'valor_iva_unidad'              => 0,
            'stock'                         => 0,
            'stock_min'                     => 0,
            'stock_max'                     => 0,
            'image'                         => $this->imagen,
            'disponible_caja'               => 1,
            'disponible_blister'            => 0,
            'disponible_unidad'             => 0,
            'contenido_interno_caja'        => 1,
            'contenido_interno_blister'     => 0,
            'contenido_interno_unidad'      => 0,
            'costo_caja'                    => $this->costo_total,
            'costo_blister'                 => 0,
            'costo_unidad'                  => 0,
            'precio_caja'                   => $this->precio_venta,
            'precio_blister'                => 0,
            'precio_unidad'                 => 0,
            'medida_id'                     => 1,
            'brand_id'                      => 1,
            'exento'                        => 0,
            'excluido'                      => 0,
            'no_gravado'                    => 0,
            'gravado'                       => 0,
            'laboratorio_id'                => 1,
            'ubicacion_id'                  => 1,
            'presentacion_id'               => 1,
            'category_id'                   => 2,
            'subcategoria_id'               => 1,
            'is_combo'                      => 1,
        ]);

        foreach($this->productosCrearCombo as $product){
            Combo::create([
                'combo_id'      => $producto->id,
                'product_id'    => $product['producto_id'],
                'forma'         => $product['forma'],
                'quantity'      => $product['cantidad'],
            ]);
        }

        return redirect()->route('inventarios.product')->with('info', 'Combo creado exitosamente, código del combo: ' . $producto->code);

    }
}
