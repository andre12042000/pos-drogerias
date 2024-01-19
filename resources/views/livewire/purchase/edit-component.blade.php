<div>
    @include('popper::assets')
    <div class="card card-info mt-1">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-8">
                    <h3>Editar factura de compra</h3>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-outline-light btn-sm mt-1 float-right" onclick="redirigir()"><i class='bi bi-arrow-left-circle'></i> Atrás</button>
                </div>
            </div>
        </div>
    <div class="card">
        <div class="card-body">


                <div class="row">
                    <div class="col-4">

                        <div class="card">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="nombre"><strong>Proveedor</strong></label>
                                    <p>{{$purchase->provider->name}}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Fecha</strong></label>
                                    <p> {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M y') }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Comprador</strong></label>
                                    <p>{{$purchase->user->name}}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Factura Nro.</strong></label>
                                    <p>{{$purchase->invoice}}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Subtotal</strong></label>
                                    <p>$ {{ number_format($purchase->total, 0) }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Iva {{$purchase->tax}} %  </strong></label>
                                    <p>$ {{ number_format($purchase->mount_tax, 0) }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Total</strong></label>

                                    <p>$ {{ number_format($purchase->total, 0) }}</p>
                                </li>
                            </ul>

                        <div class="card-footer">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-lg" wire:click="confirmacionaplicar" @if($purchase->status == "APLICADO") disabled @endif  > Aplicar en inventario</button>
                            </div>
{{-- <hr>
                            <div class="form-group row mt-3">
                                <label for="staticEmail" class="col-sm-3 col-form-label">Factura </label>
                                <div class="col-sm-9">
                                    <input type="file" wire:model.lazy="picture" class="form-control" >
                                    @error('picture')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @if ($purchase->picture != null)
                            <button class="btn btn-outline-success float-end ml-2" title="Descargar factura"><i class="bi bi-download"></i></button>

                            @endif

                            <button class="btn btn-outline-primary float-end" wire:click="facturaupdate({{ $purchase->id }})" >Actualizar</button> --}}
                        </div>
                        </div>


                    </div>



                    <div id="container" class="col-8">

                        <div class="card p-2" >

                            <div class="row" style="z-index: 10;">
                                <div class="col-5">
                                    <label for="staticEmail" class="col-sm-12 col-form-label">Producto  <i class="bi bi-plus-circle text-success" title="Crear un nuevo producto" style="cursor: pointer;" data-toggle="modal" data-target="#productomodal" @if($purchase->status == "APLICADO") disabled @endif></i></label>
                                    <input type="text" wire:model="search" class="form-control" placeholder="Buscar producto" id="search" name="search" autocomplete="off" @if($purchase->status == "APLICADO") disabled @endif >
                                    <div>
                                        @if($search AND $producto == '')
                                        <div data-kt-search-element="wrapper" style="z-index: 10; position: absolute; width:100%;"
                                            class="left-0 w-full bg-white mt-1 rounded-lg">
                                            @forelse($this->results as $result)
                                            <a href="#"
                                                class="d-flex align-items-center p-3 rounded bg-state-secondary bg-state-opacity-50 mb-1"
                                                data-kt-search-category="targets" style="width: 93%; text-decoration: none;" wire:click="seleccinarProducto({{ $result->id }})" >
                                                <!--begin::Symbol-->

                                                <!--begin::Title-->
                                                <div>
                                                    <span class="fs-6 text-gray-600 me-2 fw-bold">{{ $result->code }}</span>
                                                    <span class="fs-6 text-gray-800 me-2">{{ $result->name }}</span>
                                                </div>
                                                <!--end::Title-->
                                            </a>
                                            @empty
                                            <li class="leading-10 px-2 py-1 text-sm cursor-pointer hover:bg-gray-300">
                                                No hay ninguna coincidencia :(
                                            </li>
                                            @endforelse
                                        </div>
                                        @endif

                                    </div>

                                    @error('producto')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-4">
                                    <label for="staticEmail" class="col-sm-12 col-form-label">Vence </label>
                                    <input type="date" wire:model.lazy="fecha_vencimiento" class="form-control @if($fecha_vencimiento == '') @else @error('fecha_vencimiento') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Numero de documento" @if($purchase->status == "APLICADO") disabled @endif>
                                    @error('fecha_vencimiento')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-3">
                                    <label for="staticEmail" class="col-sm-12 col-form-label">Cantidad </label>
                                    <input type="number" wire:model.lazy="cantidad" class="form-control @if($cantidad == '') @else @error('cantidad') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Ej: 10" autocomplete="off" @if($purchase->status == "APLICADO") disabled @endif>
                                    @error('cantidad')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row mt-4">
                                <div class="form-group col-md-3">
                                  <label for="inputEmail4">Precio de compra</label>
                                  <input type="number" id="preciocompra" name="preciocompra" class="form-control @if($precio_compra == '') @else @error('precio_compra') is-invalid @else is-valid @enderror @endif" id="inputEmail4" wire:model.lazy="precio_compra" placeholder="Ej: 50000" @if($purchase->status == "APLICADO") disabled @endif>
                                  @error('precio_compra')
                                  <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>
                                <div class="col-2">
                                    <label for="inputEmail4"  >% IVA</label>
                                    <input type="number" min:0 max:30 class="form-control @if($iva_product == '') @else @error('iva_product') is-invalid @else is-valid @enderror @endif" id="inputEmail4" wire:model.lazy="iva_product" placeholder="Iva" autocomplete="off" @if($purchase->status == "APLICADO") disabled @endif>
                                    @error('iva_product')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-2">
                                  <label for="inputPassword4">% Cliente</label>
                                  <input type="number" id="cliente" name="cliente" class="form-control" id="inputPassword4" placeholder="Ej: 40" onchange="calcular()" wire:model.lazy='cliente' @if($purchase->status == "APLICADO") disabled @endif>
                                </div>
                                <div class="form-group col-md-2">
                                  <label for="inputPassword4">% Técnico</label>
                                  <input type="number" id="tecnico" name="tecnico" class="form-control" id="inputPassword4" placeholder="Ej: 30" onchange="calcular()" wire:model.lazy='tecnico' @if($purchase->status == "APLICADO") disabled @endif>
                                </div>
                                <div class="form-group col-md-3">
                                  <label for="inputPassword4">% Mayorista</label>
                                  <input type="number" id="distribuidor" name="distribuidor" class="form-control" id="inputPassword4" placeholder="Ej: 15" onchange="calcular()" wire:model.lazy='distribuidor' @if($purchase->status == "APLICADO") disabled @endif>
                                </div>

                              </div>

                              <div class="row">
                                <div class="form-group col-md-4">
                                  <label for="inputPassword4">Precio Cliente</label>
                                  <input type="number" id="precioventacliente" name="precioventacliente" class="form-control @if($precioventacliente == '') @else @error('precioventacliente') is-invalid @else is-valid @enderror @endif" id="inputPassword4" wire:model.lazy="precioventacliente" placeholder="Ej: 75000" readonly>
                                  @error('precioventacliente')
                                  <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>

                                <div class="form-group col-md-4">
                                  <label for="inputPassword4">Precio Técnico</label>
                                  <input type="number" id="precioventatecnico" name="precioventatecnico" class="form-control @if($precioventatecnico == '') @else @error('precioventatecnico') is-invalid @else is-valid @enderror @endif" id="inputPassword4" wire:model.lazy="precioventatecnico" placeholder="Ej: 45000" readonly>
                                  @error('precioventatecnico')
                                  <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>

                                <div class="form-group col-md-4">
                                  <label for="inputPassword4">Precio Mayorista</label>
                                  <input type="number" id="precioventadistribuidor" name="precioventadistribuidor" class="form-control @if($precioventadistribuidor == '') @else @error('precioventadistribuidor') is-invalid @else is-valid @enderror @endif" id="inputPassword4" wire:model.lazy="precioventadistribuidor" placeholder="Ej: 25000" readonly>
                                  @error('precioventadistribuidor')
                                  <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>
                                <div class="form-group col-md-4">
                                  <label for="inputPassword4">Descuento</label>
                                  <input type="number" id="descuento" name="descuento" class="form-control @if($descuento == '') @else @error('precioventadistribuidor') is-invalid @else is-valid @enderror @endif" id="inputPassword4" wire:model.lazy="descuento" placeholder="Ej: 25000" @if($purchase->status == "APLICADO") disabled @endif>
                                  @error('descuento')
                                  <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>

                                <div class="form-group col-md-8 ">
                                <label for="inputPassword4"></label>

                                    <div class="d-grid gap-2 mt-2">
                                        <button type="button" class="btn btn-outline-primary" wire:click="addProductInventario"  @if($purchase->status == "APLICADO") disabled @endif><i class="bi bi-plus-circle"></i> Agregar </button>
                                      </div>
                                </div>

                              </div>
                        </div>

                        <br>


                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title ml-3"><strong>Detalles de compra</strong></h4>
                            </div>
                            <div class="table-responsive col-md-12">
                                <table id="detalles" class="table">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Iva</th>
                                            <th>Descuento</th>
                                            <th class="text-right">Precio Unitario</th>
                                            <th>Cantidad</th>
                                            <th class="text-right"> Subtotal</th>
                                            <th class="text-right"> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!is_null($purchaseDetails))
                                            @foreach($purchaseDetails as $purchaseDetail)
                                            <tr>
                                                <td>{{ ucwords($purchaseDetail->product->name) }}</td>
                                                <td>$ {{number_format($purchaseDetail->mount_tax,0)}}</td>
                                                <td>$ {{number_format($purchaseDetail->discount_tax,0)}}</td>
                                                <td class="text-center">$ {{number_format($purchaseDetail->purchase_price,0)}}</td>
                                                <td class="text-center">{{$purchaseDetail->quantity}}</td>
                                                <td class="text-right">$ {{number_format($purchaseDetail->quantity*$purchaseDetail->purchase_price,0)}}</td>
                                                <td class="text-right"><button class="btn btn-outline-danger" wire:click="destroy( {{ $purchaseDetail->id }} )" style="cursor: pointer;" @if($purchase->status == "APLICADO") disabled @endif><i class="bi bi-trash3" style="font-size: 18px;  "></i></button></td>
                                            </tr>

                                            @endforeach
                                        @else
                                            <tr>
                                                <td rowspan="5" class="text-center">No se encontraron registros...</td>
                                            </tr>

                                        @endif


                                    </tbody>

                                    <tfoot>
                                        <tr class="pr-20">
                                            <th colspan="6">
                                                <p align="right">SUBTOTAL:</p>
                                            </th>
                                            <th>
                                                <p align="right"> $ {{number_format($subtotal,0)}}</p>
                                            </th>
                                        </tr>
                                        <tr class="pr-20">
                                            <th colspan="6">
                                                <p align="right">Descuento:</p>
                                            </th>
                                            <th>
                                                <p align="right"> $ {{number_format($sumadescuento,0)}}</p>
                                            </th>
                                        </tr>

                                        <tr>
                                            <th colspan="6">
                                                <p align="right">IVA: </p>
                                            </th>
                                            <th>
                                                <p align="right">$ {{number_format($sumaiva,0)}}</p>
                                            </th>
                                        </tr>
                                        <tr class="{{ $totalfull == ($purchase->total + $sumaiva - $sumadescuento)  ? "table-success" : "table-danger" }}">
                                            <th colspan="6">
                                                <p align="right">TOTAL:</p>
                                            </th>
                                            <th>
                                                <p align="right">$ {{number_format($totalfull,0) }}</p>


                                            </th>
                                        </tr>

                                    </tfoot>
                                </table>
                            </div>



                        </div>
                        @include('modals.products.create')

                        @include('modals.products.productpurchase')
                    </div>
                </div>

        </div>
    </div>


    @push('js')

<script>
    function redirigir() {
        location.replace('{!! Config::get('app.URL') !!}/inventarios/compras');
    }



</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
    window.addEventListener('confirmar_cierre_caja_event', event => {

        Swal.fire({
            title: event.detail.data['title'],
            text: event.detail.data['message'],
            icon: event.detail.data['icon'],
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, registrar!'
        }).then((result) => {
        if (result.isConfirmed) {
            Livewire.emit('confirmarEvent')
        }
        })
    })
</script>
@endpush
</div>


