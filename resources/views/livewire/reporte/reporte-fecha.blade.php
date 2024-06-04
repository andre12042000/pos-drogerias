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
                            Desde <input type="date" class="form-control" id="inlineFormInputGroupUsername" placeholder="Username" wire:model='filter_desde' id="desde" name="desde" max="{{ date('Y-m-d') }}">
                            @error('filter_desde')
                            <span class="text-white">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-5 my-1">
                            Hasta
                            <input type="date" class="form-control" id="inlineFormInputGroupUsername" placeholder="Username" wire:model='filter_hasta' id="hasta" name="hasta" max="{{ date('Y-m-d') }}">
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
                    <span class="col-lg-12">{{$count}}</span>
                </div>
                <div class=" col-lg-2 float-right text-right">
                    <div class="dropdown mr-4 mt-3">
                        <button class="btn btn-outline-light dropdown-toggle" @if($ventas->isEmpty()) disabled @endif href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-expanded="false">
                            Generar Recibo
                            <div wire:loading wire:target="exportarventas">
                                <img src="{!! Config::get('app.URL') !!}/img/loading.gif" width="20px"
                                    class="img-fluid" alt="">
                            </div>

                            <div wire:loading wire:target="imprimirInforme">
                                <img src="{!! Config::get('app.URL') !!}/img/loading.gif" width="20px"
                                    class="img-fluid" alt="">
                            </div>
                        </button>

                        <ul class="dropdown-menu text-dark" aria-labelledby="dropdownMenuLink">
                            <li> <a style="cursor: pointer" class="dropdown-item text-dark" wire:click="exportarventas"><i class="bi bi-download"></i>
                                    Descargar Excel

                                </a></li>
                            <li> <a style="cursor: pointer" href="JavaScript:void(0);" class="dropdown-item text-dark"
                                    wire:click="imprimirInforme"> <i class="bi bi-printer"></i> Imprimir informe</a>
                            </li>



                        </ul>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-4">

                    <div class="card">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item-primary d-flex justify-content-between align-items-center">
                                <label class="form-control-label ml-2" for="nombre"><strong>TOTAL
                                        RECAUDO</strong></label>
                                <p class="mr-2 mt-2 text-bold">$ {{ number_format($totalVenta + $pagoCreditos, 0) }}</p>
                            </li>
                            <li class="list-group-item-info d-flex justify-content-between align-items-center">
                                <label class="form-control-label ml-2" for="nombre"><strong>ABONOS</strong></label>
                                <p class="mr-2 mt-2 text-bold">$ {{ number_format($totalAbono, 0) }}</p>
                            </li>
                            <li class="list-group-item-info d-flex justify-content-between align-items-center">
                                <label class="form-control-label ml-2" for="nombre"><strong>RECAUDOS CARTERA</strong>
                                    <i class="bi bi-info-circle-fill" data-toggle="tooltip" data-placement="top"
                                        title="Si no se recibió el dinero en mostrador o caja, resta el valor al TOTAL RECAUDO."></i></label>
                                <p class="mr-2 mt-2 text-bold">$ {{ number_format($pagoCreditos, 0) }}</p>
                            </li>
                            <li class="list-group-item-info d-flex justify-content-between align-items-center">
                                <label class="form-control-label ml-2" for="nombre"><strong>OTROS
                                        CONCEPTOS</strong></label>
                                <p class="mr-2 mt-2 text-bold">$ {{ number_format($OtrosConceptos, 0) }}</p>
                            </li>

                            <!-- Mostrar información de métodos de pago -->
                            @foreach ($metodosDePagoGroup as $nombreMetodoPago => $total)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label"
                                        for="nombre"><strong>{{ $nombreMetodoPago }}</strong></label>
                                    <p>$ {{ number_format($total, 0) }}</p>
                                </li>
                            @endforeach
                            <!-- Fin de la información de métodos de pago -->

                        </ul>
                    </div>

                    <div class="card">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label " for="nombre"><strong>VENTAS
                                        ANULADAS</strong></label>
                                <p class="mr-2 mt-2 text-bold">$ {{ number_format($facturasAnuladas, 0) }}</p>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label" for="nombre"><strong>CONSUMO INTERNO
                                    </strong></label>

                                <p>$ {{ number_format($totalConsumoInterno, 0) }}</p>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label" for="nombre"><strong>GASTOS
                                        OPERACIONALES</strong></label>
                                <p>$ {{ number_format($totalGastos, 0) }}</p>
                            </li>

                        </ul>
                    </div>



                </div>

                <div class="col-8">

                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-4 text-center">
                                    <h1 class="card-title ml-3 mt-2"><strong >Detalles</strong></h1>

                                </div>
                                <div class="col-4">
                                    <select class="form-select detalles-select" id="opcionesDetalles"
                                    wire:model = 'user'>
                                    <option value="">Usuarios</option>

                                    @foreach ($usuarios as $user)
                                    <option value="{{$user->id}}">{{mb_strtoupper($user->name)}}</option>
                             @endforeach
                                </select>
                                </div>
                                <div class="col-4 detalles-select-wrapper">
                                    <select class="form-select detalles-select" id="opcionesDetalles"
                                        wire:model = 'filtro_operaciones'>
                                        <option value="">Todas operaciones</option>
                                        <option value="App\Models\Sale">Ventas</option>
                                        <option value="App\Models\PagoCreditos">Recaudo Cartera</option>
                                        <option value="App\Models\Gastos">Gastos</option>
                                        <option value="App\Models\Abonos" disabled>Abonos</option>
                                        <option value="App\Models\ConsumoInterno" disabled>Consumo Interno</option>
                                    </select>
                                </div>
                            </div>



                            <!-- Agrega el siguiente select al final del card-header -->

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
                                        <th>Metodo de pago</th>
                                        <th>Total</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ventas as $venta)
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
                                                @if ($venta->cashesable_type == 'App\Models\Gastos' OR $venta->cashesable_type == 'App\Models\ConsumoInterno')
                                                    N/A
                                                @else
                                                    {{ ucwords($venta->cashesable->client->name) }}
                                                @endif
                                            </td>
                                            {{-- Metodo de pago  --}}
                                            <td>
                                                @php
                                                $metodopago = $venta->cashesable->metodopago;

                                                // Intentar decodificar JSON
                                                $decoded = json_decode($metodopago);

                                                // Verificar si la decodificación fue exitosa y tiene el campo 'name'
                                                if (json_last_error() === JSON_ERROR_NONE && isset($decoded->name)) {
                                                    $metodopagoName = $decoded->name;
                                                } else {
                                                    // Asumir que es una cadena de texto simple
                                                    $metodopagoName = $metodopago;
                                                }
                                            @endphp

                                            {{ mb_strtoupper($metodopagoName) }}

                                            </td>


                                            {{-- Cantidad  --}}

                                            <td class="text-end">$ {{ number_format($venta->quantity, 0) }}</td>
                                            <td class="text-center">
                                                {{-- Cuidado por que imprime segun el modelo ventas o abonos --}}

                                                @if ($venta->cashesable_type == 'App\Models\Sale')
                                                    <a @popper(Ver comprobante) class="btn btn-outline-primary btn-sm"
                                                        href="{{ route('ventas.pos.details', $venta->cashesable_id) }}">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>

                                                    <a @popper(Imprimir comprobante) class="btn btn-outline-success btn-sm"
                                                        href="{{ route('ventas.pos.imprimir.recibo', $venta->cashesable_id) }}">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                @elseif ($venta->cashesable_type == 'App\Models\PagoCreditos')
                                                    <a @popper(Ver comprobante) class="btn btn-outline-primary btn-sm"
                                                        href="{{ route('detalle.pagocredito', $venta->cashesable_id) }}">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>

                                                    <a @popper(Imprimir recibo) class="btn btn-outline-success btn-sm"
                                                        href="{{ route('imprimir.pago_venta_credito', $venta->cashesable_id) }}">
                                                        <i class="bi bi-printer"></i>
                                                    </a>

                                                @elseif($venta->cashesable_type == 'App\Models\ConsumoInterno')

                                                <a @popper(Imprimir comprobante) class="btn btn-outline-success btn-sm"
                                                        href="{{ route('consumo_interno.imprimir', $venta->cashesable_id) }}">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        <tr>
                                        @empty
                                        <tr>
                                            <td colspan="10">
                                                <p>No se encontraron registros...</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>


                        </div>
                    </div>
                    @if ($ventas->count() >= $cantidad_registros)
                        <div class="card-footer ">
                            <nav class="" aria-label="">
                                <ul class="pagination">
                                    {{ $ventas->links() }}
                                </ul>
                            </nav>

                        </div>
                    @endif



                </div>
            </div>

        </div>
    </div>
</div>













        </div>
    </div>
</div>
