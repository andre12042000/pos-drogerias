<?php

namespace App\Http\Livewire\Product;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Laboratorio;
use App\Models\Presentacion;
use App\Models\Provider;
use App\Models\UnidadMedida;
use Livewire\WithFileUploads;
use App\Models\PurchaseDetail;
use App\Models\Subcategoria;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\Storage;

class CreateComponent extends Component
{
    use WithFileUploads;
    public $categories, $providers, $unidades, $marcas, $purchase_id;
    public $status = 'ACTIVE';
    public $codigo, $producto, $categoria, $proveedor, $factura_compra, $stock_actual, $stock_minimo, $stock_maximo, $precio_compra, $precioventacliente, $precioventatecnico, $precioventadistribuidor;
    public $unidad_medida, $marca, $fecha_vencimiento, $imagen, $photo;
    public $nuevacategoria, $nuevamarca;
    public $name, $code, $stock, $stock_max, $stock_min, $image, $sell_price, $category_id, $medida_id, $brand_id, $expiration, $selected_id;
    public $cliente, $tecnico, $distribuidor;
    public $iva_product = 0;

    public $sell_price_tecnico, $sell_price_distribuidor, $last_price, $subcategorias, $subcategory_id, $exento, $excluido, $no_gravado, $gravado, $contenido_interno_caja;
    public $contenido_interno_blister, $contenido_interno_unidad, $costo_caja, $costo_blister, $costo_unidad, $ubicacion_id, $laboratorio_id, $presentacion_id ;
    public $laboratorios, $ubicaciones, $presentaciones;

    /*--------------------------------------------------------------------------------------
    ----------- Procesos disponibles solo mientras se cargan el inventario inicial        --
    ---------------------------------------------------------------------------------------*/

    public $precio_venta_actual = 0;

    public function UpdatedPrecioVentaActual()
    {
        $this->precio_compra = round($this->precio_venta_actual / 1.35, 2);
        $this->UpdatedPrecioCompra();
    }


    /*---------------------------------------------------------------------------------------------------
    -----------               Fin de los procesos usados mientras se carga inventario inicial         ---
    ---------------------------------------------------------------------------------------------------*/

    //Listeners y eventos

    protected $listeners = ['CategoryEvent','ProductEventEdit', 'ProviderEvent', 'BrandEvent', 'precioventavalidate', 'cierre_modal'];

    public function cierre_modal(){
        $this->dispatchBrowserEvent('close-modal');
    }

    public function mount($purchase)
    {
        if(!is_null($purchase))
        {
            $this->purchase_id          = $purchase->id;
            $this->proveedor            = $purchase->provider_id;
            $this->factura_compra       = $purchase->invoice;
        }

    }

    public function UpdatedPrecioCompra()
    {
        if($this->precio_compra > 100000){
            $this->cliente      = 30;
        }elseif($this->precio_compra > 500000){
            $this->cliente      = 25;
        }else{
            $this->cliente      = 35;
        }

        $this->tecnico      = 10;
        $this->distribuidor = 15;

        $this->calcularPrecios();

    }

    public function calcularPrecios()
    {
        $this->precioventacliente = $this->precio_compra + ($this->precio_compra * $this->cliente / 100);
        $this->precioventatecnico = $this->precioventacliente - ($this->precioventacliente * $this->tecnico / 100);
        $this->precioventadistribuidor = $this->precioventacliente - ($this->precioventacliente * $this->distribuidor / 100);

        $this->precioventacliente = round($this->precioventacliente);
        $this->precioventatecnico = round($this->precioventatecnico);

        $this->precioventadistribuidor = round($this->precioventadistribuidor);




        $this->verificarporcentajesdescuento();
    }

    public function verificarporcentajesdescuento()
    {
        if($this->tecnico >= $this->cliente OR $this->distribuidor >= $this->cliente)
        {
            session()->flash('error', 'Cuidado, verifique los valores de descuentos!');
        }
    }

    public function updatedCliente()
    {
        $this->calcularPrecios();
    }

    public function updatedTecnico()
    {
        $this->calcularPrecios();
    }
    public function updatedDistribuidor()
    {
        $this->calcularPrecios();
    }


    public function BrandEvent($brand)
    {
        $this->marcas = Brand::all();
        $this->marca = $brand['id'];
    }

    public function ProductEventEdit($product)
    {

        $this->selected_id              = $product['id'];
        $this->producto                 = $product['name'];
        $this->codigo                   = $product['code'];
        $this->stock_actual             = $product['stock'];
        $this->stock_maximo             = $product['stock_max'];
        $this->stock_minimo             = $product['stock_min'];
        $this->photo                    = $product['image'];
        $this->categoria                = $product['category_id'];
        $this->precioventacliente       = $product['sell_price'];
        $this->precioventatecnico       = $product['sell_price_tecnico'];
        $this->precioventadistribuidor  = $product['sell_price_distribuidor'];
        $this->status                   = $product['status'];
        $this->iva_product              = $product['iva_product'];
        $this->precio_compra            = $product['last_price'];

        if($this->precio_compra > 0){
            $this->cliente = ((100 * $this->precioventacliente) / $this->precio_compra) - 100;
        }else{
            $this->cliente = 0;
            $this->tecnico = 0;
            $this->distribuidor = 0;
        }
        if($this->precioventacliente > 0){
             $this->tecnico = (((100 * $this->precioventatecnico) / $this->precioventacliente) - 100) * -1;
        $this->distribuidor = (((100 * $this->precioventadistribuidor) / $this->precioventacliente) - 100) * -1;
        }


        $this->verificarporcentajesdescuento();


    }

    public function CategoryEvent($category)
    {
        $this->categories = Category::all();
        $this->categoria = $category['id'];
    }

    public function ProviderEvent()
    {
        $this->providers = Provider::all();
    }

    //Validaciones

    protected $rules = [
        'codigo'                => 'required|min:3|max:100|unique:products,code',
        'producto'              => 'required|min:2|max:100|unique:products,name',
        'stock_actual'          => 'numeric|required|min:0|max:10000',
        'stock_minimo'          => 'numeric|required|min:0|max:10000',
        'stock_maximo'          => 'numeric|required|min:0|max:10000',
        'precio_compra'         => 'numeric|required|min:0|max:100000000',
        'unidad_medida'         => 'nullable',
        'marca'                 => 'nullable',
        'fecha_vencimiento'     => 'nullable',
        'image'                 => 'nullable|image|max:2048|mimes:jpg,jpeg,bmp,png',
        'iva_product'          => 'numeric|min:0|max:30',
    ];

    protected $messages = [
        'codigo.required'       => 'Este campo es requerido',
        'codigo.min'            => 'El código debe tener al menos 6 carácteres',
        'codigo.max'            => 'El código no puede tener mas de 100 dígitos',
        'codigo.unique'         => 'Este código ya ha sido registrado',
        'producto.required'     => 'El campo nombre es requerido',
        'producto.min'          => 'El campo nombre debe tener al menos 2 carácteres',
        'producto.max'          => 'El campo nombre no puede superar los 254 carácteres',
        'producto.unique'       => 'El campo nombre ya se encuentra registrado',
        'stock_actual.min:'     => 'El campo stock debe tener al menos 0 dígitos',
        'stock_actual.max:'     => 'El campo stock no puede tener mas de 10000 dígitos',
        'address.min:'          => 'El campo dirección debe tener al menos 8 carácteres',
        'address.max:'          => 'El campo dirección no puede tener mas de 100 carácteres',
        'email.max:'            => 'El campo email no puede tener mas de 255 carácteres',
        'email.email:'          => 'El campo email no es una dirección válida',
        'image.image'           => 'El formato no es valido',
        'image.max'             => 'Supero el peso permitido 2MB',
        'iva_product.numeric'   => 'Este campo debe ser numerico',
        'iva_product.min'       => 'El IVA debe ser al menos 0',
        'iva_product.max'       => 'El IVA no puede superar 30% ',
    ];

    public function updatedNuevacategoria()
    {
        $this->validate([
            'nuevacategoria' =>  'required|min:4|max:100|unique:categories,name',
        ],[
            'nuevacategoria.required'          => 'Este campo es requerido',
            'nuevacategoria.min'               => 'Este campo requiere al menos 4 carácteres',
            'nuevacategoria.max'               => 'Este campo no puede superar los 100 carácteres',
            'nuevacategoria.unique'            => 'Este categoría ya existe',
        ]);
    }

    public function updatedNuevamarca()
    {
        $this->validate([
            'nuevamarca' =>  'required|min:2|max:100|unique:brands,name',
        ],[
            'nuevamarca.required'          => 'Este campo es requerido',
            'nuevamarca.min'               => 'Este campo requiere al menos 2 carácteres',
            'nuevamarca.max'               => 'Este campo no puede superar los 100 carácteres',
            'nuevamarca.unique'            => 'Esta marca ya existe',
        ]);
    }

    public function storeOrupdate()
    {

        if($this->selected_id > 0){
            $this->update();

        }else{
            $this->save();
        }

        $this->emit('reloadProductos');
    }

    public function render()
    {

        $this->categories = Category::orderBy('name', 'ASC')->get();
        $this->providers = Provider::all();
        $this->unidades = UnidadMedida::all();
        $this->marcas = Brand::all();
        $this->subcategorias = Subcategoria::where('status', 'ACTIVE') ->orderBy('name', 'asc')->get();
        $this->ubicaciones = Ubicacion::where('status', 'ACTIVE') ->orderBy('name', 'asc')->get();
        $this->presentaciones = Presentacion::where('status', 'ACTIVE') ->orderBy('name', 'asc')->get();
        $this->laboratorios = Laboratorio::where('status', 'ACTIVE') ->orderBy('name', 'asc')->get();



        return view('livewire.product.create-component');
    }

    //Guardado

    public function save()
    {
        $this->validate();

        if( $this->fecha_vencimiento == ''){
            $this->fecha_vencimiento = null;
        }

        if($this->image){
            $photo = $this->image->store('livewire-tem');
        }else{
            $photo = null;
        }

        $product = Product::create([
            'code'                       => $this->codigo,
            'name'                       => strtolower($this->producto),
            'stock'                      => $this->stock_actual,
            'iva_product'                => $this->iva_product,
            'sell_price'                 => $this->precioventacliente,
            'stock_min'                  => $this->stock_minimo,
            'stock_max'                  => $this->stock_maximo,
            'sell_price_tecnico'         => $this->precioventatecnico ,
            'sell_price_distribuidor'    => $this->precioventadistribuidor,
            'status'                     => $this->status,
            'last_price'                 => $this->precio_compra ,
            'category_id'                => $this->categoria,
            'provider_id'                => $this->proveedor,
            'medida_id'                  => $this->unidad_medida,
            'brand_id'                   => $this->marca,
            'expiration'                 => $this->fecha_vencimiento,
            'image'                      => $photo,
        ]);

        if(!is_null($this->purchase_id)){
            $this->addDetailPurchase($product);
            $this->emit('ReloadDetailsPurchase', $this->purchase_id);
        }

        $this->cancel();

        $this->emit('ProductsEvent', $product);
        $this->cierre_modal();
        session()->flash('message', 'Producto creado exitosamente');
    }

    public function addDetailPurchase($product)
    {
        PurchaseDetail::create([
            'purchase_id'           =>  $this->purchase_id,
            'product_id'            =>  $product->id,
            'caducidad'             =>  $product->expiration,
            'quantity'              =>  $product->stock,
            'purchase_price'        =>  $this->precio_compra,
            'sales_price'           =>  $this->precio_venta,
        ]);

    }

    public function cancel()
    {
            $this->reset();
            $this->resetErrorBag();
    }
//actualizar

    public function update()
    {
        $this->validate(
            [
                'codigo'                => 'required|min:3|max:100|unique:products,code,' . $this->selected_id,
                'producto'              => 'required|min:2|max:100|unique:products,name,' . $this->selected_id,
                'stock_actual'          => 'numeric|required|min:0|max:10000',
                'stock_minimo'          => 'numeric|required|min:0|max:10000',
                'stock_maximo'          => 'numeric|required|min:0|max:10000',
                'precio_compra'         => 'numeric|required|min:0|max:100000000',
                'unidad_medida'         => 'nullable',
                'marca'                 => 'nullable',
                'fecha_vencimiento'     => 'nullable',
                'iva_product.numeric'   => 'Este campo debe ser numerico',
                'iva_product.min'       => 'El IVA debe ser al menos 0',
                'iva_product.max'       => 'El IVA no puede superar 30% ',
            ]

        );

        $products = Product::find($this->selected_id);

        if($this->image){
            $photo = $this->image->store('livewire-tem');
        }else{
            $photo = null;
        }

        $products->update([
            'code'                      => $this->codigo,
            'name'                      => strtolower($this->producto),
            'stock'                     => $this->stock_actual,
            'stock_min'                 => $this->stock_minimo,
            'stock_max'                 => $this->stock_maximo,
            'sell_price'                => $this->precioventacliente,
            'last_price'                => $this->precio_compra ,
            'category_id'               => $this->categoria,
            'sell_price_tecnico'        => $this->precioventatecnico,
            'sell_price_distribuidor'   => $this->precioventadistribuidor,
            'image'                     => $photo,
            'status'                     => $this->status,
            'iva_product'                => $this->iva_product,

        ]);


        $this->cancel();
        $this->dispatchBrowserEvent('close-modal');
        $this->dispatchBrowserEvent('alert');

    }


    //Metodos para agregar entidades fuertes

    public function guardarCategoria()
    {
        $this->validate([
            'nuevacategoria' =>  'required|min:4|max:100|unique:categories,name',
        ],[
            'nuevacategoria.required'          => 'Este campo es requerido',
            'nuevacategoria.min'               => 'Este campo requiere al menos 4 carácteres',
            'nuevacategoria.max'               => 'Este campo no puede superar los 100 carácteres',
            'nuevacategoria.unique'            => 'Este categoría ya existe',
        ]);

        $category = Category::create([
            'name'  => strtolower($this->nuevacategoria),
        ]);

        $this->nuevacategoria = '';

        $this->emit('CategoryEvent', $category);
    }

    public function guardarMarca()
    {
        $this->validate([
            'nuevamarca' =>  'required|min:2|max:100|unique:brands,name',
        ],[
            'nuevamarca.required'          => 'Este campo es requerido',
            'nuevamarca.min'               => 'Este campo requiere al menos 2 carácteres',
            'nuevamarca.max'               => 'Este campo no puede superar los 100 carácteres',
            'nuevamarca.unique'            => 'Esta marca ya existe',
        ]);

        $marca = Brand::create([
            'name'     =>  $this->nuevamarca,
        ]);

        $this->nuevamarca = '';
        $this->emit('BrandEvent', $marca);
    }

    public function updatedCategoryId(){

        $this->subcategorias = Subcategoria::where('status', 'ACTIVE')->where('category_id', $this->category_id)->orderBy('name', 'asc')->get();
    }
}
