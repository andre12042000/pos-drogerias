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
                 <!-- Mostrar información de métodos de pago -->
                 @foreach($sumatoriasMetodosPago as $nombreMetodo => $cantidad)
                 <li class="list-group-item d-flex justify-content-between align-items-center">
                     <label class="form-control-label" for="nombre"><strong>{{ $nombreMetodo }}</strong></label>
                     <p>$ {{ number_format($cantidad, 0) }}</p>
                 </li>
             @endforeach
             <!-- Fin de la información de métodos de pago -->
            </ul>
        </div>


        <div class="card">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label " for="nombre"><strong>Venta Anulada</strong></label>
                    <p class="mr-2 mt-2 text-bold">$ {{ number_format($totalAnulado)}}</p>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label " for="nombre"><strong>Venta Credito</strong></label>
                    <p class="mr-2 mt-2 text-bold">$ 0</p>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="nombre"><strong>Consumo Interno</strong></label>
                    <p>$ 0</p>
                </li>

            </ul>
        </div>


<div class="card">
<ul class="list-group list-group-flush">
<li class="list-group-item d-flex justify-content-between align-items-center">
<label class="form-control-label" for="nombre"><strong>Ventas Cajero</strong></label>
</li>
@foreach ($vendedor as $resultado)
<li class="list-group-item d-flex justify-content-between align-items-center">
<label class="form-control-label" for="nombre"><strong>{{ $resultado->user->name }}</strong></label>
<p class="mr-2 mt-2 text-bold">$ {{ number_format($resultado->total_quantity, 0) }}</p>
</li>
@endforeach
</ul>
</div>
    </div>
    <div class="col-8">
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

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ventas as $venta)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($venta->created_at)->format('d-m-Y h:i A') }}</td>
                            <td>@if ($venta->cashesable_type == 'App\Models\Sale')
                                <span class="badge badge-pill badge-primary">
                                    Venta</span>
                                @else
                                <span class="badge badge-pill badge-info">
                                    Abono</span>
                                @endif
                            </td>
                            <td>{{ $venta->cashesable->full_nro }}</td>
                            <td>{{ ucwords($venta->cashesable->user->name) }}</td>
                            <td>{{ ucwords($venta->cashesable->client->name) }}</td>
                            <td class="text-end">$ {{ number_format( $venta->quantity, 0) }}</td>

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
    </div>
</div>
