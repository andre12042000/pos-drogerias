<strong style="color: red">Reporte Generado Por Sistema FacilPos </strong>
<h2>Resumen de ingresos y salidas</h2>

<table class="table table-striped" id="tabMarcas">
    <thead>
        <tr>

            <th> <strong> Pago Creditos </strong></th>
            <th> <strong> Abonos </strong></th>
            <th> <strong> Gastos </strong></th>
            <th> <strong> Consumo Interno </strong></th>
            <th> <strong> Facturas Anuladas </strong></th>
        </tr>
    </thead>
    <tbody>

        <tr>

            <td> $ {{ number_format($totales['pagoCreditos'], 0) }}</td>
            <td> $ {{ number_format($totales['totalAbono'], 0) }}</td>
            <td> $ {{ number_format($totales['totalGastos'], 0) }}</td>
            <td> $ {{ number_format($totales['totalConsumoInterno'], 0) }}</td>
            <td> $ {{ number_format($totales['facturasAnuladas'], 0) }}</td>
        </tr>


    </tbody>
</table>
<h2>Ingresos por metodos de pago</h2>

@foreach ($totales['metodosDePagoGroup'] as $nombreMetodoPago => $total)
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <label class="form-control-label" for="nombre"><strong>{{ $nombreMetodoPago }}</strong></label>
        <p>$ {{ number_format($total, 0) }}</p>
    </li>
@endforeach

<h3 class="float-end text-end">Total Recaudo $ {{ number_format($totales['totalventa'] + $totales['pagoCreditos'], 0) }}
</h3>

<h3>Reporte Compras </h3>

<div class="table-responsive col-md-12">
    <table class="table table-striped">
        <thead>
            <tr>
                <th> <strong>Fecha</strong> </th>
                <th> <strong>Proveedor</strong> </th>
                <th> <strong>Metodo de pago</strong> </th>
                <th> <strong>Total</strong> </th>

            </tr>
        </thead>
        <tbody>
            @forelse ($compras as $compra)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($compra->created_at)->format('d-m-Y h:i A') }}</td>
                    <td>{{ ucwords($compra->provider->name) }}</td>
                    <td>{{ ucwords($compra->metodopago->name) }}</td>
                    <td>$ {{ number_format($compra->total) }}</td>

                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        <p>No se encontraron registros...</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<h3>Reporte Pago Crédito </h3>

<div class="table-responsive col-md-12">
    <table class="table table-striped" id="tabProducts">
        <thead>
            <tr>
                <th> <strong>Fecha</strong> </th>
                <th> <strong>Tipo</strong> </th>
                <th> <strong>Código</strong> </th>
                <th> <strong>Vendedor/a</strong> </th>
                <th> <strong>Cliente</strong> </th>
                <th> <strong>Metodo de pago</strong> </th>
                <th> <strong>Total</strong> </th>

            </tr>
        </thead>
        <tbody>
            @forelse ($ventas as $venta)
                @if ($venta->cashesable_type == 'App\Models\PagoCreditos')
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($venta->created_at)->format('d-m-Y h:i A') }}</td>
                        {{-- Tipo de operacion  --}}

                        <td>
                            @if ($venta->cashesable_type == 'App\Models\Sale')
                                @if ($venta->cashesable->tipo_operacion == 'VENTA')
                                    <span class="badge badge-pill badge-primary">
                                        Venta</span>
                                @else
                                    <span class="badge badge-pill badge-warning">
                                        Crédito</span>
                                @endif
                            @elseif ($venta->cashesable_type == 'App\Models\PagoCreditos')
                                <span class="badge badge-pill badge-success">
                                    Pago crédito</span>
                            @elseif ($venta->cashesable_type == 'App\Models\Gastos')
                                <span class="badge badge-pill badge-danger">
                                    Gasto</span>
                            @elseif ($venta->cashesable_type == 'App\Models\ConsumoInterno')
                                <span class="badge badge-pill badge-secondary">
                                    Consumo Interno</span>
                            @else
                                <span class="badge badge-pill badge-info">
                                    Abono</span>
                            @endif

                        </td>

                        <td>{{ $venta->cashesable->full_nro }}</td>


                        <td>{{ ucwords($venta->cashesable->user->name) }}</td>
                        <td>


                            @if ($venta->cashesable_type == 'App\Models\Gastos' or $venta->cashesable_type == 'App\Models\ConsumoInterno')
                                N/A
                            @else
                                {{ ucwords($venta->cashesable->client->name) }}
                            @endif
                        </td>

                        <td>
                            @if ($venta->cashesable->metodopago)
                                {{ ucwords($venta->cashesable->metodopago->name) }}
                            @else
                                N/A
                            @endif

                        </td>
                        <td>$ {{ number_format($venta->quantity, 0) }}</td>

                    </tr>
                @else
                @endif

            @empty
                <tr>
                    <td colspan="7">
                        <p>No se encontraron registros...</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<h3>Reporte Gastos </h3>

<div class="table-responsive col-md-12">
    <table class="table table-striped">
        <thead>
            <tr>
                <th> <strong>Fecha</strong> </th>
                <th> <strong>Tipo</strong> </th>
                <th> <strong>Código</strong> </th>
                <th> <strong>Vendedor/a</strong> </th>
                <th> <strong>Cliente</strong> </th>
                <th> <strong>Metodo de pago</strong> </th>
                <th> <strong>Total</strong> </th>

            </tr>
        </thead>
        <tbody>

            @forelse ($ventas as $venta)
                @if ($venta->cashesable_type == 'App\Models\Gastos')
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($venta->created_at)->format('d-m-Y h:i A') }}</td>
                        {{-- Tipo de operacion  --}}
                        <td>
                            @if ($venta->cashesable_type == 'App\Models\Sale')
                                @if ($venta->cashesable->tipo_operacion == 'VENTA')
                                    <span class="badge badge-pill badge-primary">
                                        Venta</span>
                                @else
                                    <span class="badge badge-pill badge-warning">
                                        Crédito</span>
                                @endif
                            @elseif ($venta->cashesable_type == 'App\Models\PagoCreditos')
                                <span class="badge badge-pill badge-success">
                                    Pago crédito</span>
                            @elseif ($venta->cashesable_type == 'App\Models\Gastos')
                                <span class="badge badge-pill badge-danger">
                                    Gasto</span>
                            @elseif ($venta->cashesable_type == 'App\Models\ConsumoInterno')
                                <span class="badge badge-pill badge-secondary">
                                    Consumo Interno</span>
                            @else
                                <span class="badge badge-pill badge-info">
                                    Abono</span>
                            @endif

                        </td>
                        {{-- Fin Tipo de operacion  --}}

                        {{-- Consecutivo de operacion  --}}
                        <td>{{ $venta->cashesable->full_nro }}</td>

                        {{-- Usuario que registro  --}}
                        <td>{{ ucwords($venta->cashesable->user->name) }}</td>
                        <td>

                            {{-- Cliente  --}}
                            @if ($venta->cashesable_type == 'App\Models\Gastos' or $venta->cashesable_type == 'App\Models\ConsumoInterno')
                                N/A
                            @else
                                {{ ucwords($venta->cashesable->client->name) }}
                            @endif
                        </td>
                        {{-- Metodo de pago  --}}
                        <td>
                            @if ($venta->cashesable->metodopago)
                                {{ ucwords($venta->cashesable->metodopago->name) }}
                            @else
                                N/A
                            @endif

                        </td>


                        {{-- Cantidad  --}}

                        <td class="text-end">$ {{ number_format($venta->quantity, 0) }}</td>

                    </tr>
                @endif

            @empty
                <tr>
                    <td colspan="7">
                        <p>No se encontraron registros...</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<h3>Reporte Consumo Interno </h3>

<div class="table-responsive col-md-12">
    <table class="table table-striped">
        <thead>
            <tr>
                <th> <strong>Fecha</strong> </th>
                <th> <strong>Tipo</strong> </th>
                <th> <strong>Código</strong> </th>
                <th> <strong>Vendedor/a</strong> </th>
                <th> <strong>Cliente</strong> </th>
                <th> <strong>Metodo de pago</strong> </th>
                <th> <strong>Total</strong> </th>

            </tr>
        </thead>
        <tbody>
            @forelse ($ventas as $venta)
                @if ($venta->cashesable_type == 'App\Models\ConsumoInterno')
                    <tr @if ($venta->quantity == '0') class="table-danger" @endif>
                        <td>{{ \Carbon\Carbon::parse($venta->created_at)->format('d-m-Y h:i A') }}
                        </td>
                        {{-- Tipo de operacion  --}}
                        <td>
                            @if ($venta->cashesable_type == 'App\Models\Sale')
                                @if ($venta->cashesable->tipo_operacion == 'VENTA')
                                    <span class="badge badge-pill badge-primary">
                                        Venta</span>
                                @else
                                    <span class="badge badge-pill badge-warning">
                                        Crédito</span>
                                @endif
                            @elseif ($venta->cashesable_type == 'App\Models\PagoCreditos')
                                <span class="badge badge-pill badge-success">
                                    Pago crédito</span>
                            @elseif ($venta->cashesable_type == 'App\Models\Gastos')
                                <span class="badge badge-pill badge-danger">
                                    Gasto</span>
                            @elseif ($venta->cashesable_type == 'App\Models\ConsumoInterno')
                                <span class="badge badge-pill badge-secondary">
                                    Consumo Interno</span>
                            @else
                                <span class="badge badge-pill badge-info">
                                    Abono</span>
                            @endif

                        </td>


                        {{-- Fin Tipo de operacion  --}}

                        {{-- Consecutivo de operacion  --}}
                        <td>{{ $venta->cashesable->full_nro }}</td>

                        {{-- Usuario que registro  --}}
                        <td>{{ ucwords($venta->cashesable->user->name) }}</td>
                        <td>

                            {{-- Cliente  --}}
                            @if ($venta->cashesable_type == 'App\Models\Gastos' or $venta->cashesable_type == 'App\Models\ConsumoInterno')
                                N/A
                            @else
                                {{ ucwords($venta->cashesable->client->name) }}
                            @endif
                        </td>
                        {{-- Metodo de pago  --}}
                        <td>
                            @if ($venta->cashesable->metodopago)
                                {{ ucwords($venta->cashesable->metodopago->name) }}
                            @else
                                N/A
                            @endif

                        </td>


                        {{-- Cantidad  --}}

                        <td class="text-end">$ {{ number_format($venta->quantity, 0) }}</td>

                    </tr>
                @endif

            @empty
                <tr>
                    <td colspan="7">
                        <p>No se encontraron registros...</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<h3>Reporte Ventas </h3>

<div class="table-responsive col-md-12">
    <table class="table table-striped">
        <thead>
            <tr>
                {{--    detalles  --}}
                <th> <strong>Producto</strong> </th>
                <th> <strong>Forma</strong> </th>
                <th> <strong>Cantidad</strong> </th>
                <th> <strong>Precio</strong> </th>
                {{-- venta --}}
                <th> <strong>Tipo</strong> </th>
                <th> <strong>Código</strong> </th>
                <th> <strong>Metodo de pago</strong> </th>
                <th> <strong>Total</strong> </th>

            </tr>
        </thead>
        <tbody>

            @foreach ($ventas as $venta)
                @if ($venta->cashesable_type == 'App\Models\Sale')
                    @foreach ($venta->cashesable->saleDetails as $detalle)
                        <tr>
                            <td>{{ $detalle->product->name }}</td>
                            <td>
                                @if ($detalle->forma == 'disponible_caja')
                                    Caja
                                @elseif ($detalle->forma == 'disponible_blister')
                                    Blister
                                @else
                                    Unidad
                                @endif

                            </td>
                            <td>{{ $detalle->quantity }}</td>
                            <td>{{ $detalle->quantity * $detalle->price }}</td>
                            <td>
                                @if ($venta->cashesable_type == 'App\Models\Sale')
                                    @if ($venta->cashesable->tipo_operacion == 'VENTA')
                                        <span class="badge badge-pill badge-primary">
                                            Venta</span>
                                    @else
                                        <span class="badge badge-pill badge-warning">
                                            Crédito</span>
                                    @endif
                                @elseif ($venta->cashesable_type == 'App\Models\PagoCreditos')
                                    <span class="badge badge-pill badge-success">
                                        Pago crédito</span>
                                @elseif ($venta->cashesable_type == 'App\Models\Gastos')
                                    <span class="badge badge-pill badge-danger">
                                        Gasto</span>
                                @elseif ($venta->cashesable_type == 'App\Models\ConsumoInterno')
                                    <span class="badge badge-pill badge-secondary">
                                        Consumo Interno</span>
                                @else
                                    <span class="badge badge-pill badge-info">
                                        Abono</span>
                                @endif

                            </td>

                            <td>{{ $venta->cashesable->full_nro }}</td>

                            <td>
                                @if ($venta->cashesable->metodopago)
                                    {{ ucwords($venta->cashesable->metodopago->name) }}
                                @else
                                    N/A
                                @endif

                            </td>
                            {{-- total  --}}
                            <td class="text-end">$ {{ number_format($venta->quantity, 0) }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
</div>
