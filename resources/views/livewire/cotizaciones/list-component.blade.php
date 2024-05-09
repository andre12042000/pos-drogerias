<div>
    @section('title', 'Cotizaciones')

    @section('content_header')

        <div class="row float-right mr-1">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Movimientos</li>

                    <li class="breadcrumb-item active" aria-current="page">Cotizaciones</li>
                </ol>
            </nav>
        </div>

        <br>
        @stop
    <div> @include('includes.alert')</div>
    @include('popper::assets')
    <div class="card card-info  mt-2">
        <div class="card-header">

            <div class="row">
                <div class="col-sm-6">
                    <h3>Cotizaciones</h3>
                </div>


                <div class="col-sm-6">
                    <div class="input-group float-right">
                        <select wire:model="cantidad_registros" class="form-select col-sm-2 mr-2"
                            aria-label="Default select example">
                            <option value="10"> 10 </option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar cotización" aria-label="Username"
                            aria-describedby="basic-addon1" wire:model="buscar">

                        <a  class="btn btn-light text-black float-right ml-2" href="{{ route('cotizaciones.cotizaciones.create') }}" >Nueva Cotización <i class="las la-plus-circle"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th class="text-end">Descuento</th>
                        <th class="text-end">IVA</th>
                        <th class="text-end">Total</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cotizaciones as $cotizacion)
                    <tr>
                        <td>{{ $cotizacion->full_nro }} </td>
                        <td>{{ ucwords($cotizacion->client->name) }} </td>
                        <td>{{ \Carbon\Carbon::parse($cotizacion->cotzacion_date)->format('d M Y') }}</td>
                        <td class="text-end">$ {{ number_format($cotizacion->discount, 0) }} </td>
                        <td class="text-end">$ {{ number_format($cotizacion->tax, 0) }} </td>
                        <td class="text-end">$ {{ number_format($cotizacion->total, 0) }}</td>

                        <td class="text-center">
                            <a class="btn btn-outline-secondary" href="{{ route('cotizaciones.generarpdfcotizacion', $cotizacion->id) }}" title="Descargar PDF"> <i class="bi bi-filetype-pdf"></i>  </a></li>

                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <p>No se encontraron registros...</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <nav aria-label="...">
                <ul class="pagination">
                    {{ $cotizaciones->links() }}
                </ul>
            </nav>

        </div>
    </div>
</div>
@include('modals.cotizaciones.createcotizacion')
<script>
      window.addEventListener('alert', () => {
            Swal.fire({
                icon: "success",
                title: "Registro actualizado correctamente",
                showConfirmButton: false,
                timer: 1500
            });


        });
</script>
