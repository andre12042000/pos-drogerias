<?php

namespace App\Http\Livewire\Product;

use App\Models\Combo;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class ComboEditComponent extends Component
{
    use WithFileUploads;
    protected $listeners = ['agregarProductoCrearCombo' => 'addProduct'];
    public $productosCrearCombo = [];
    public $costo_total = 0;
    public $precio_sugerido = 0;
    public $iva = 0;
    public $nueva_cantidad, $selected_combo;
    public $valor_adicionar = 0;

    public $codigo, $nombre, $precio_venta, $imagen, $photo;
    public $error_cantidad_productos = false;

    protected $rules = [
        'nombre'            => 'required|min:3|max:255',
        'costo_total'       => 'required',
        'precio_venta'      => 'required|min:0'
    ];

    public function mount($combo_id){
        $this->selected_combo = $combo_id;
        $producto = Product::find($combo_id);
      $this->nombre = $producto->name;
      $this->codigo = $producto->code;
      $this->photo = $producto->image;
      $this->precio_venta = $producto->precio_caja;
     $this->costo_total = $producto->costo_caja;


      $this->iva = $producto->valor_iva_caja;

      $this->precio_sugerido = round((($this->costo_total * 30)/100) + $this->costo_total);

    $combos = Combo::where('combo_id', $combo_id)->get();

    foreach($combos as $combo){
        if($combo->forma == 'disponible_caja'){
            $costo_unitario = $combo->product->costo_caja;
            $forma = 'Caja';
        }elseif($combo->forma == 'disponible_blister'){
            $costo_unitario = $combo->product->costo_blister;
            $forma = 'Blister';
        }else{
            $costo_unitario = $combo->product->costo_unidad;
            $forma = 'Unidad';
        }
        $total = $costo_unitario * $combo->quantity;

        $this->productosCrearCombo[] = [
            'id'                    => $combo->id,
            'producto_id'           => $combo->product_id,
            'name'                  => $combo->product->name,
            'codigo'                => $combo->product->code,
            'forma'                 => $combo->forma,
            'forma_presentacion'    => $forma,
            'cantidad'              => $combo->quantity,
            'costo_unitario'        => $costo_unitario,
            'total'                 => $total
        ];
    }


    }
    public function aumentarcantidad($id){
        $combo = Combo::find($id);
        $actualizacion = $combo->quantity + 1;
        $producto_actualizar = Product::find($this->selected_combo);
        $actualizacion = $combo->quantity + 1;

        if($combo->forma == 'disponible_caja'){
            $costo_unitario = $combo->product->costo_caja;
            $forma = 'Caja';
        }elseif($combo->forma == 'disponible_blister'){
            $costo_unitario = $combo->product->costo_blister;
            $forma = 'Blister';
        }else{
            $costo_unitario = $combo->product->costo_unidad;
            $forma = 'Unidad';
        }
        $valor_aumentar = $costo_unitario;

        $producto_actualizar->update([
            'costo_caja'  => $producto_actualizar->costo_caja + $valor_aumentar,
        ]);
        $combo->update([
            'quantity'  => $actualizacion,
        ]);

        $this->productosCrearCombo = [];
        $this->mount($this->selected_combo);

    }

    public function disminuircantidad($id){
        $combo = Combo::find($id);
        $producto_actualizar = Product::find($this->selected_combo);
        $actualizacion = $combo->quantity - 1;

        if($combo->forma == 'disponible_caja'){
            $costo_unitario = $combo->product->costo_caja;
            $forma = 'Caja';
        }elseif($combo->forma == 'disponible_blister'){
            $costo_unitario = $combo->product->costo_blister;
            $forma = 'Blister';
        }else{
            $costo_unitario = $combo->product->costo_unidad;
            $forma = 'Unidad';
        }
        $valor_restar = $costo_unitario;

        $producto_actualizar->update([
            'costo_caja'  => $producto_actualizar->costo_caja - $valor_restar,
        ]);



        $combo->update([
            'quantity'  => $actualizacion,
        ]);

        $this->productosCrearCombo = [];
        $this->mount($this->selected_combo);

    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.product.combo-edit-component')->extends('adminlte::page');
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
        self::calcularCostoTotal();
    }

    public function update()
    {
        $photo = $this->imagen;

        if(isset($photo) && $photo instanceof \Illuminate\Http\UploadedFile){
            $this->imagen = $photo->store('livewire-tem');
        } else {
            $this->imagen = null;
        }
        self::calcularCostoTotal();
        $this->validate();

        $this->validate([
            'codigo'            => 'required|min:3|max:255|unique:products,code,' . $this->selected_combo,
        ]);

        if (count($this->productosCrearCombo) < 2) {
            $this->error_cantidad_productos = true;
        }else{
            $this->error_cantidad_productos = false;
        }
        $producto_actualizar = Product::find($this->selected_combo);

        $producto_actualizar->update([

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

       // Obtener los combos existentes según el combo seleccionado
$combos = Combo::where('combo_id', $this->selected_combo)->get();

// Array para almacenar los IDs de los productos a sincronizar
$productosIds = [];

// Recorrer los productos existentes
foreach ($this->productosCrearCombo as $product) {
    // Verificar si el producto existe en los combos
    $comboExistente = $combos->where('product_id', $product['producto_id'])->first();

    if ($comboExistente) {
        // Si el combo existe, actualizarlo
        $comboExistente->update([
            'forma' => $product['forma'],
            'quantity' => $product['cantidad'],
        ]);
    } else {
        // Si el combo no existe, crearlo
        Combo::create([
            'combo_id' => $this->selected_combo,
            'product_id' => $product['producto_id'],
            'forma' => $product['forma'],
            'quantity' => $product['cantidad'],
        ]);
    }

    // Agregar el ID del producto al array de IDs
    $productosIds[] = $product['producto_id'];
}

// Eliminar los combos que no están presentes en $this->productosCrearCombo
$combos->whereNotIn('product_id', $productosIds)->each(function ($combo) {
    $combo->delete();
});



        return redirect()->route('inventarios.product')->with('info', 'Combo actualizado exitosamente, código del combo: ' . $producto_actualizar->code);

    }
}
