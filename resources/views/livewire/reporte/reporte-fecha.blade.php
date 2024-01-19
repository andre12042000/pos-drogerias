<div>
    @include('popper::assets')
    <div class="card card-info">
        <div class="card-header">
            <div class="form-row">
                <div class="mt-3 col-lg-3">
                    <h3>Reportes fecha</h3>
                </div>
                <div class="text-center col-lg-5">
                    <div class="row">
                        <div class="col-sm-5 my-1">
                            Desde <input type="date" class="form-control" id="inlineFormInputGroupUsername" placeholder="Username" wire:model='filter_desde' id="desde" name="desde">
                            @error('filter_desde')
                            <span class="text-white">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-5 my-1">
                            Hasta
                            <input type="date" class="form-control" id="inlineFormInputGroupUsername" placeholder="Username" wire:model='filter_hasta' id="hasta" name="hasta">
                            @error('filter_hasta')
                            <span class="text-white">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-2 mt-4 my-1">

                        </div>

                    </div>

                </div>

                <div class="text-center col-lg-2 mt-2">
                    <label class="col-lg-12">Cantidad de registros</label>
                    <span class="col-lg-12">{{$cantidad}}</span>
                </div>
                <div class="text-end col-lg-2">
                <a class="btn btn-outline-light mt-3 text-dark"  href="{{route('reporte.export.fecha', ['desde' => $desde, 'hasta' => $hasta])}}" ><i class="bi bi-file-earmark-arrow-down"></i> <strong> Exportar Excel</strong></a>
            </div>
              
            </div>
        </div>
        <div class="card-body">


                <div class="row">
                    <div class="col-4">

                        <div class="card">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item-primary d-flex justify-content-between align-items-center">
                                    <label class="form-control-label ml-2" for="nombre"><strong>Venta directa</strong></label>
                                    <p class="mr-2 mt-2 text-bold">$ {{ number_format($venta, 0) }}</p>
                                </li>
                                <li class="list-group-item-info d-flex justify-content-between align-items-center">
                                    <label class="form-control-label ml-2" for="nombre"><strong>Abonos</strong></label>
                                    <p class="mr-2 mt-2 text-bold">$ {{ number_format($abono, 0) }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="nombre"><strong>Efectivo</strong></label>
                                    <p>$ {{ number_format($efectivo, 0) }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Tarjeta</strong></label>
                                    <p>$ {{ number_format($tarjeta, 0) }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Transferencia o QR</strong></label>
                                    <p>$ {{ number_format($transferencia, 0) }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Cheque</strong></label>
                                    <p>$ {{ number_format($cheque, 0) }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Deposito</strong></label>
                                    <p>$ {{ number_format($deposito, 0) }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Total</strong></label>

                                    <p class="text-bold">$ {{ number_format($total, 0) }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-8">

                        @if($ventas != '')
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title ml-3"><strong>Detalles</strong></h4>
                            </div>

                            <div class="table-responsive col-md-12">
                                <table class="table table-striped" id="tabProducts">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Tipo</th>
                                            <th>Código</th>
                                            <th>Vendedor/a</th>
                                            <th>Cliente</th>
                                            <th>Total</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($ventas as $venta)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($venta->created_at)->format('d M Y h:i A') }}</td>
                                            <td>@if ($venta->cashesable_type == 'App\Models\Sale')
                                                <span class="badge badge-pill badge-primary">
                                                    Venta</span>
                                                @else
                                                <span class="badge badge-pill badge-info">
                                                    Abono</span>
                                                @endif
                                            </td>
                                            <td>{{ $venta->cashesable->full_nro }}</td>
                                            <td>{{ $venta->cashesable->user->name }}</td>
                                            <td>{{ $venta->cashesable->client->name }}</td>
                                            <td class="text-end">$ {{ number_format( $venta->quantity, 0) }}</td>
                                            <td class="text-center">

                                                <a @popper(Ver comprobante) class="btn btn-outline-primary btn-sm" href="@if ($venta->cashesable_type == 'App\Models\Sale')
                                                    {{ route('ventas.pos.details', $venta->cashesable_id) }}
                                                @else
                                                   {{ route('orders.show', $venta->cashesable->abonable_id) }}
                                                @endif" target="_blank" role="button"><i class="bi bi-eye-fill"></i></a>

                                            </td>
                                        <tr>
                                            @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer ">
                                <nav class="" aria-label="">
                                    <ul class="pagination">
                                        {{ $ventas->links() }}
                                    </ul>
                                </nav>

                            </div>


                        </div>
                        @else
                        <div class="card">
                            <div class="card-header">Selecciona un rango de fecha para filtrar. </div>

                        </div>
                        @endif



                    </div>
                </div>

        </div>
    </div>
</div>
