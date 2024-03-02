
    <div class="row mt-4">
        <div class="col-lg-4">
            <h4 class="text-center"><strong>Información Básica</strong></h4>
            <ul class="mt-5">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="nombre"><strong>Factura</strong></label>
                    <p class="text-bold">{{$sales->full_nro }}</p>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="nombre"><strong>Cliente</strong></label>
                    <p>{{ mb_strtoupper($sales->client->name)}}</p>
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
                    <p>{{  mb_strtoupper($sales->user->name)}}</p>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="num_compra"><strong>Estado</strong></label>
                     @if ($sales->status == 'ANULADA')
                        <span class="badge badge-danger"> ANULADA </span>
                     @else
                        <span class="badge badge-success"> APROBADA </span>
                     @endif
                </li>

                @if ($sales->valor_anulado > 0)

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="num_compra"><strong>Valor anulado</strong></label>
                    <p><strong>$ {{ number_format($sales->valor_anulado, 0) }} </strong></p>
                </li>

                @endif
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
                            <th class="text-center">Forma</th>
                            <th class="text-center">Precio Unitario</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-right"> Subtotal</th>

                        </tr>
                    </thead>
                    <tbody> @foreach ($detailsales as $detalles)
                        <tr>
                            <td class="text-center">{{ mb_strtoupper($detalles->product->name) }}</td>
                            <td class="text-center">@if ($detalles->forma == 'disponible_caja')
                                Caja
                            @elseif ($detalles->forma == 'disponible_unidad')
                                Unidad
                                @else
                                Blister
                            @endif</td>

                            <td class="text-right"> $ {{number_format( $detalles->price,0)}}</td>
                            <td class="text-center">{{ $detalles->quantity}}</td>
                            <td class="text-right">$ {{  number_format($detalles->price *  $detalles->quantity ,0)}}</td>
                            <br>

                        </tr> @endforeach
                    </tbody>

                    <tfoot>

                        <tr class="">
                            <th colspan="4">
                                <h3 class="text-end">TOTAL:</h1>
                            </th>
                            <th>
                                <h3 class="text-end">$ {{number_format( $sales->total,0)}}</h3>
                            </th>
                        </tr>

                    </tfoot>
                </table>
            </div>
        </div>
    </div>

