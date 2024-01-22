<?php

namespace App\Http\Livewire\Purchase;

use App\Models\Product;
use Livewire\Component;
use App\Models\Purchase;
use App\Traits\UpdateProduct;
use App\Models\PurchaseDetail;
use Illuminate\Support\Facades\DB;

class EditComponent extends Component
{
    use UpdateProduct;
    public $purchase, $products, $purchaseDetails, $subtotal, $totalfull, $search, $iva_product, $sumaiva, $sumadescuento;
public $descuento = 0;
    public $producto, $fecha_vencimiento, $cantidad, $precio_compra, $precio_venta, $precioventacliente, $precioventatecnico, $precioventadistribuidor;
    public $cliente, $tecnico, $distribuidor;
    public $productsadd = [];
    public $totalalert = 1000;
    protected $listeners = ['ReloadDetailsPurchase', 'precioventavalidate', 'confirmarEvent', 'ProductsEvent'];

    public function ProductsEvent($product)
    {
      $this->seleccinarProducto($product['id']);
      $this->emit('cierre_modal');
    }
    public function confirmarEvent(){

        $this->aplicar();
    }
    protected $rules = [
        'producto'              => 'required|min:6|max:100',
        'fecha_vencimiento'     => 'nullable',
        'cantidad'              => 'numeric|required|min:1|max:100000',
        'precio_compra'         => 'numeric|required|min:100|max:100000000',
        'precio_venta'          => 'numeric|required|min:100|max:100000000',
    ];

    protected $messages = [
        'codigo.required'       => 'Este campo es requerido',
        'codigo.min'            => 'El código debe tener al menos 6 carácteres',
        'codigo.max'            => 'El código no puede tener mas de 100 dígitos',
        'producto.required'     => 'El campo nombre es requerido',
        'producto.min'          => 'El campo nombre debe tener al menos 6 carácteres',
        'producto.max'          => 'El campo nombre no puede superar los 254 carácteres',
        'producto.unique'       => 'El campo nombre ya se encuentra registrado',
        'stock_actual.min:'     => 'El campo telefono debe tener al menos 8 dígitos',
        'stock_actual.max:'     => 'El campo telefono no puede tener mas de 20 dígitos',
        'address.min:'          => 'El campo dirección debe tener al menos 8 carácteres',
        'address.max:'          => 'El campo dirección no puede tener mas de 100 carácteres',
        'email.max:'            => 'El campo email no puede tener mas de 255 carácteres',
        'email.email:'          => 'El campo email no es una dirección válida',
        'iva_product.numeric'   => 'Este campo debe ser numerico',
        'iva_product.min'       => 'El IVA debe ser al menos 0',
        'iva_product.max'       => 'El IVA no puede superar 30% ',
    ];



    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function ReloadDetailsPurchase($purchase)
    {

        $this->clearData();

        $this->purchase = Purchase::find($purchase);

        $this->purchaseDetails = $this->purchase->purchaseDetails;


        if ($this->purchaseDetails->count() > 0) {
            foreach ($this->purchaseDetails as $purchaseDetail) {
                $this->subtotal = ($purchaseDetail->quantity * $purchaseDetail->purchase_price) + $this->subtotal;
                $this->sumaiva += $purchaseDetail->mount_tax;
                $this->sumadescuento += $purchaseDetail->discount_tax;
            }

            $this->totalfull = $this->sumaiva + $this->subtotal;


        } else {
            $this->totalfull = 0;
            $this->subtotal = 0;
        }
    }





    private function clearData()
    {
        $this->products = '';
        $this->purchaseDetails;
        $this->subtotal = 0;
        $this->totalfull = 0;
        $this->search = '';
        $this->producto = '';
        $this->fecha_vencimiento = '';
        $this->cantidad = '';
        $this->precio_compra = 0;
        $this->precio_venta = 0;
        $this->precioventacliente = 0;
        $this->precioventatecnico = 0;
        $this->precioventadistribuidor = 0;
        $this->cliente = 0;
        $this->tecnico = 0;
        $this->distribuidor = 0;
        $this->productsadd = [];
        $this->iva_product = 0;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($purchase)
    {

        $this->purchase = $purchase;

        $subtotal = 0;

        $this->purchaseDetails = $purchase->purchaseDetails;

        foreach ($this->purchaseDetails as $purchaseDetail) {

            $this->subtotal = $this->subtotal +  ($purchaseDetail->quantity * $purchaseDetail->purchase_price);
            $this->sumaiva += $purchaseDetail->mount_tax;
            $this->sumadescuento += $purchaseDetail->discount_tax;
        }

        $this->totalfull = $this->sumaiva + $this->subtotal - $this->sumadescuento;
    }

    public function precioventavalidate($precioventa)
    {
        $this->precio_venta = $precioventa;

        $this->validate([
            'precio_venta' => 'required',
        ]);
    }



    public function render()
    {
        $this->products = Product::active()->get();

        return view('livewire.purchase.edit-component');
    }

   public function confirmacionaplicar()
    {
         if ($this->totalfull == ($this->purchase->total + $this->sumaiva)) {

                 $this->aplicar();

        } else {

            $this->totalalert = $this->totalfull - $this->purchase->total;

            $data = [
                'icon'      => 'warning',
                'title'     => '¿Registrar Compra?',
                'message'   => 'Valor compra: $' . number_format($this->purchase->total) . ' tienes un saldo de diferencia de: $' . number_format($this->totalalert),

            ];

            $this->dispatchBrowserEvent('confirmar_cierre_caja_event', ['data' => $data]);
        }

    }





    public function getResultsProperty()
    {
        return Product::where(DB::raw('concat_ws(" ", code, name)'), 'like', "%" . $this->search . "%")->take(8)->get();
    }

    public function seleccinarProducto($product)
    {
        $buscarproducto = Product::find($product);

        if ($buscarproducto) {
            $this->search = $buscarproducto->name;
            $this->producto = $buscarproducto->id;
            $this->iva_product = $buscarproducto->iva_product;
            $this->precio_compra = $buscarproducto->last_price;
            $this->precioventacliente = $buscarproducto->precio_caja;
            $this->precioventatecnico = $buscarproducto->precio_blister;
            $this->precioventadistribuidor = $buscarproducto->precio_unidad;

            $this->cliente = ((100 * $this->precioventacliente) / $this->precio_compra) - 100;
            $this->tecnico = ((100 * $this->precioventatecnico) / $this->precio_compra) - 100;
            $this->distribuidor = ((100 * $this->precioventadistribuidor) / $this->precio_compra) - 100;
        }
    }

    public function addProductInventario()
    {
        if($this->iva_product > 0){
            $mount = ($this->precio_compra * $this->iva_product)/100;
        }else{
            $mount = 0;
        }
        if($this->descuento > 0){
            $discount_tax = ($this->precio_compra * $this->descuento)/100;
        }else{
            $discount_tax = 0;
        }


        $this->validate([
            'producto'                      => 'required',
            'fecha_vencimiento'             => 'nullable',
            'cantidad'                      => 'required|numeric|min:1|max:100000',
            'precio_compra'                 => 'required|numeric|min:0|max:100000000',
            'precioventacliente'            => 'required|numeric|min:0|max:100000000',
            'precioventatecnico'            => 'required|numeric|min:0|max:100000000',
            'precioventadistribuidor'       => 'required|numeric|min:0|max:100000000',
        ], [
            'producto.required'             => 'Este campo es requerido',
            'cantidad.required'             => 'Este campo es requerido',
            'cantidad.min'                  => 'Debe ser un caracter numerico',
            'cantidad.min'                  => 'Este campo requiere al menos 1 carácteres',
            'cantidad.max'                  => 'Este campo no puede superar los 100000 carácteres',
            'precio_compra.required'        => 'Este campo es requerido',
            'precio_compra.min'             => 'Debe ser un caracter numerico',
            'precio_compra.min'             => 'Este campo requiere al menos 100 caracteres',
            'precio_compra.max'             => 'Este campo no puede superar los 100000000 caracteres',
            'precio_venta.required'         => 'Este  campo  es  requerido',
            'precio_venta.min'              => 'Debe ser un caracter numerico',
            'precio_venta.min'              => 'Este campo requiere al menos 100 caracteres',
            'precio_venta.max'              => 'Este campo no puede superar los 100000000 caracteres',
        ]);

        $compra = PurchaseDetail::create([
            'purchase_id'               => $this->purchase->id,
            'product_id'                => $this->producto,
            'caducidad'                 => $this->fecha_vencimiento,
            'quantity'                  => $this->cantidad,
            'purchase_price'            => $this->precio_compra,
            'sales_price'               => $this->precioventacliente,
            'precio_blister'        => $this->precioventatecnico,
            'precio_unidad'   => $this->precioventadistribuidor,
            'tax'                       => $this->iva_product,
            'discount'                  => $this->descuento,
            'mount_tax'                 => $mount,
            'discount_tax'              => $discount_tax,
        ]);

        $this->clearData();

        $this->emit('ReloadDetailsPurchase', $this->purchase->id);
    }

    public function destroy($id)
    {
        $purchasedetail = PurchaseDetail::find($id);
        $purchasedetail->delete();
        $this->emit('ReloadDetailsPurchase', $this->purchase->id);
        session()->flash('warning', 'Producto fuera de la compra');
    }

    public function aplicar()
    {

        if ($this->purchaseDetails->isEmpty()) {
            session()->flash('message', 'Debes agregar un producto almenos para ingresar la factura!!');

        }else{
            foreach ($this->purchaseDetails as $detail) {
                $sum_products = [];
                $sum_products[] = [
                    'id'            => $detail['product_id'],
                    'quantity'      => $detail['quantity'],
                ];

                $this->addProduct($sum_products);
                $this->updatePrices($detail);
            }

            $this->purchase->update([
                'status'        => 'APLICADO',
            ]);

            session()->flash('message', 'Factura cargada correctamente.');
        }

        return redirect(route('inventarios.purchase'));
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

    public function updatedPreciocompra()
    {
        $this->calcularPrecios();
    }

    public function calcularPrecios()
    {
        $this->precioventacliente = $this->precio_compra + ($this->precio_compra * $this->cliente / 100);
        $this->precioventatecnico = $this->precio_compra + ($this->precio_compra * $this->tecnico / 100);
        $this->precioventadistribuidor = $this->precio_compra + ($this->precio_compra * $this->distribuidor / 100);
    }

    public function facturaupdate($purchase){

        /* $this->validate([
            'name'       =>  'required|min:4|max:254'
          ]);

            $category = Category::find($this->selected_id);

            $category->update([
                'name'  => strtolower($this->name),
            ]);  */
    }
}
