<div>
    @include('popper::assets')
    @include('includes.alert')
<div class="card card-info mt-2">
    <div class="card-header">
    <div class="row">
                <div class="col-sm-6">
                <h3>Compras</h3>
                </div>
                <div class="col-sm-6">
                <div class="input-group float-right">
                <select  wire:model="cantidad_registros" class="form-select col-sm-2 mr-2" aria-label="Default select example">
                        <option value="10"> 10 </option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                </select>
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar compra" aria-label="Username"
                            aria-describedby="basic-addon1"  wire:model = "buscar">
                            @can('Acceso Inventraio Crear')
                            <button type="button" class="btn btn-outline-light float-right ml-2" data-toggle="modal" data-target="#purchasemodal" >Nueva compra <i class="las la-plus-circle"></i></button>

                            @endcan

                    </div>
                </div>

            </div>
</div>
        <div class="card-body">
            <table class="table table-striped" id="tabPurchases">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Nro. Factura</th>
                        <th>Cant. Productos</th>
                        <th class="text-center">Iva</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Comprobante</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                     @forelse ($purchaes as $purchase)
                        <tr>
                           <td>{{ $purchase->fecha_compra }}</td>
                           <td>{{ $purchase->provider->name }}</td>
                           <td>{{ $purchase->invoice }}</td>
                           <td>{{ $purchase->cantproducts }}</td>
                           <td class="text-end">{{ $purchase->iva }}</td>
                           <td class="text-end">{{ $purchase->total_compra }}</td>


                            <td class="text-center">
                                @if($purchase->picture)
                                    <img src="{!! Config::get('app.URL') !!}/storage/{{ $purchase->picture }}" width="50px"
                                        height="50px">

                                @else
                                    <img src="{{ asset('img/sinimagen.jpg') }}"
                                        width="50px">
                                @endif
                            </td>
                           <td>@if($purchase->status == 'BORRADOR') <span class="badge bg-warning ">Pendiente</span> @else <span class="badge bg-success">Añadida</span> @endif </td>
                           <td class="text-center">
                           @can('Acceso Inventario Editar')
                           <a @popper(Abonar) class="btn btn-outline-info btn-sm @if($purchase->abono == $purchase->total || $purchase->payment_method == '2') disabled @endif" href="#" role="button"
                                data-toggle="modal" data-target="#abonocompramodal"
                                wire:click="sendDataAbono( {{ $purchase }} )" ><i class="bi bi-currency-dollar"></i></a>

                           <a href="{{ route('inventarios.purchase.edit', $purchase) }}" @popper(Editar) class="btn btn-outline-success btn-sm" href="#"><i class="bi bi-pencil-square"></i></a>

                                @endcan
                                <a  @popper(Eliminar)  wire:click="destroy( {{ $purchase->id }} )" class="btn btn-outline-danger btn-sm" ><i class="bi bi-trash3"></i></a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="9">
                                <p>No se encontraron registros...</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer ">
            <nav class="" aria-label="">
                <ul class="pagination">
                  {{ $purchaes->links() }}
                </ul>
              </nav>

        </div>
    </div>
</div>
@include('modals.abono.compras')


