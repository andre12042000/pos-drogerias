<div class="card-body">
    <div class="row">
        <div class="col-lg-4">
            <h4 class="text-center"><strong>Información Básica</strong></h4>
            <ul class="mt-5">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="nombre"><strong>Factura</strong></label>
                    <p class="text-bold">{{$sales->full_nro }}</p>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="nombre"><strong>Cliente</strong></label>
                    <p>{{$sales->client->name}}</p>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="num_compra"><strong>Identificación</strong></label>
                    <p> @if($sales->client->number_document != '') {{$sales->client->number_document}} @else <span> Sin ingresar </span> @endif</p>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="num_compra"><strong>Fecha de venta</strong></label>
                    <p> {{ \Carbon\Carbon::parse($sales->created_at)->format('d M Y') }} </p>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="num_compra"><strong>Vendedor</strong></label>
                    <p>{{$sales->user->name}}</p>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="num_compra"><strong>Estado</strong></label>
                     @if ($sales->status == 'ANULADA')
                      <span class="badge badge-danger"> ANULADA </span>
                     @else
                     <span class="badge badge-success"> APROBADA </span>
                     @endif
                </li>
            </ul>
        </div>

        <div class="col-lg-1"></div>

        <div class="col-lg-7">

            <div class=" table-responsive  ">
            <h4 class="text-center"><strong> Detalles de venta</strong></h4>

                <table id="detalles" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">Producto</th>
                            <th class="text-right">Forma</th>
                            <th class="text-center">Precio Unitario</th>
                            <th class="text-right">Cantidad</th>
                            <th class="text-right"> Subtotal</th>

                        </tr>
                    </thead>
                    <tbody> @foreach ($detailsales as $detalles)
                        <tr>
                            <td class="text-center">{{ $detalles->product->name}}</td>
                            <td class="text-center">@if ($detalles->forma == 'disponible_caja')
                                Caja
                            @elseif ($detalles->forma == 'disponible_unidad')
                                Unidad
                                @else
                                Blister
                            @endif</td>

                            <td class="text-right"> $ {{number_format( $detalles->price,0)}}</td>
                            <td class="text-right">{{ $detalles->quantity}}</td>
                            <td class="text-right">$ {{  $detalles->price *  $detalles->quantity}}</td>
                            <br>

                        </tr> @endforeach
                    </tbody>

                    <tfoot>

                        <tr class="">
                            <th colspan="3">
                                <p class="text-end">TOTAL:</p>
                            </th>
                            <th>
                                <p class="text-end">$ {{number_format( $sales->total,0)}}</p>
                            </th>
                        </tr>

                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
