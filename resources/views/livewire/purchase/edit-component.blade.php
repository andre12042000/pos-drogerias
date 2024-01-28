<div>
    @include('popper::assets')
    <div class="card card-info mt-1">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-8">
                    <h3>Detalles factura de compra</h3>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-outline-light btn-sm mt-1 float-right" onclick="redirigir()"><i
                            class='bi bi-arrow-left-circle'></i> Atrás</button>
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
                                    <p>{{ $purchase->provider->name }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Fecha de
                                            compra</strong></label>
                                    <p> {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M y') }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label"
                                        for="num_compra"><strong>Comprador</strong></label>
                                    <p>{{ $purchase->user->name }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Factura
                                            Nro.</strong></label>
                                    <p>{{ $purchase->invoice }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Subtotal</strong></label>
                                    <p>$ {{ number_format($purchase->total, 0) }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Descuento
                                        </strong></label>
                                    <p>$ {{ number_format($purchase->discount, 0) }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Iva {{ $purchase->tax }}
                                            % </strong></label>
                                    <p>$ {{ number_format($purchase->mount_tax, 0) }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Total</strong></label>

                                    <p>$ {{ number_format($purchase->total, 0) }}</p>
                                </li>
                            </ul>

                            <div class="card-footer">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-lg" wire:click="confirmacionaplicar"
                                        @if ($purchase->status == 'APLICADO') disabled @endif> Aplicar en
                                        inventario</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card col-8">
                        <div class="card-header">
                            <h4 class="card-title mb-0 mt-2"><strong>Detalles de compra</strong></h4>
                            <div class="float-right">
                                <button class="btn btn-outline-primary" type="button" data-toggle="modal"
                                    data-target="#productomodal">Nuevo producto <i
                                        class="bi bi-plus-circle"></i></button>
                                <button class="btn btn-outline-success" type="button" data-toggle="modal"
                                    data-target="#searchproductsemodal" wire:click="enviarDataVentaModalAddProducto">Añadir productos <i
                                        class="bi bi-search"></i></button>
                            </div>
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

                                    @forelse ($purchaseDetails as $purchaseDetail)
                                        <tr>
                                            <td>{{ ucwords($purchaseDetail->product->name) }}</td>
                                            <td>$ {{ number_format($purchaseDetail->mount_tax, 0) }}</td>
                                            <td>$ {{ number_format($purchaseDetail->discount_tax, 0) }}</td>
                                            <td class="text-center">$
                                                {{ number_format($purchaseDetail->purchase_price, 0) }}</td>
                                            <td class="text-center">{{ $purchaseDetail->quantity }}</td>
                                            <td class="text-right">$
                                                {{ number_format($purchaseDetail->quantity * $purchaseDetail->purchase_price, 0) }}
                                            </td>
                                            <td class="text-right"><button class="btn btn-outline-danger"
                                                    wire:click="destroy( {{ $purchaseDetail->id }} )"
                                                    style="cursor: pointer;"
                                                    @if ($purchase->status == 'APLICADO') disabled @endif><i
                                                        class="bi bi-trash3" style="font-size: 18px;  "></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr style="height: 100px;">
                                            <td colspan="7" class="text-center">
                                                <p class="mt-4"><em>Para empezar agregue los productos realizados en
                                                        la compra... </em></p>
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                                <tfoot>
                                    <tr class="pr-20">
                                        <th colspan="6">
                                            <p align="right">SUBTOTAL:</p>
                                        </th>
                                        <th>
                                            <p align="right"> $ {{ number_format($subtotal, 0) }}</p>
                                        </th>
                                    </tr>
                                    <tr class="pr-20">
                                        <th colspan="6">
                                            <p align="right">Descuento:</p>
                                        </th>
                                        <th>
                                            <p align="right"> $ {{ number_format($sumadescuento, 0) }}</p>
                                        </th>
                                    </tr>

                                    <tr>
                                        <th colspan="6">
                                            <p align="right">IVA: </p>
                                        </th>
                                        <th>
                                            <p align="right">$ {{ number_format($sumaiva, 0) }}</p>
                                        </th>
                                    </tr>
                                    <tr
                                        class="{{ $totalfull == $purchase->total + $sumaiva - $sumadescuento ? 'table-success' : 'table-danger' }}">
                                        <th colspan="6">
                                            <p align="right">TOTAL:</p>
                                        </th>
                                        <th>
                                            <p align="right">$ {{ number_format($totalfull, 0) }}</p>


                                        </th>
                                    </tr>

                                </tfoot>
                            </table>
                        </div>
                    </div>
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
