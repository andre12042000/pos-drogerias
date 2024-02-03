<div>

    @section('title', 'Ubicaciones')

    @section('content_header')

        <div class="row float-right mr-1">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Facturación</li>

                    <li class="breadcrumb-item active" aria-current="page">Anulación</li>
                </ol>
            </nav>
        </div>

        <br>
    @stop


    <div class="card card-danger">
        <div class="card-header">
            <div class="form-row">
                <div class="mt-2 col-lg-4">
                    <h3>Anular factura {{ $sales->full_nro }}</h3>
                </div>



                <div class="text-end col-lg-8">

                    <form>
                        <div class="form-group row mt-2">
                            <label for="colFormLabelLg" class="col-sm-4 col-form-label col-form-label-lg">Motivo de
                                anulación</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <select class="form-select form-control-l " @if ($sales->status == 'ANULADA')
                                        disabled
                                    @endif id="inputGroupSelect04"
                                        {{ $status }} wire:model.lazy = 'motivo_anulacion'>
                                        <option selected>Seleccione un motivo de anulación...</option>
                                        @forelse ($motivo_anulaciones as $motivo)
                                            <option value="{{ $motivo->id }}">{{ $motivo->name }}</option>
                                        @empty
                                            <option>No hay datos disponibles, registre motivos de anulación...</option>
                                        @endforelse
                                    </select>

                                    <div class="input-group-append">
                                        <button type="button" @if ($sales->status == 'ANULADA')
                                            disabled
                                        @endif class="btn btn-outline-light btn-lg" {{ $status }}
                                            wire:click="anularFactura">Anular</button>
                                    </div>
                                </div>
                                @error('motivo_anulacion')
                                    <span class="text-white">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </form>
                    {{-- <a class="btn btn-outline-light mt-2 text-dark @if ($cantidad == 0) disabled @endif"
                        href="{{ route('reporte.export.dia') }}"><i class="bi bi-file-earmark-arrow-down"></i> <strong>
                            Exportar Excel</strong></a> --}}
                </div>

            </div>
        </div>
 <div class="card-body ">
    @include('includes.bodyfactura')
</div>
        @if ($status == 'disabled')
            <div class="row m-4">
                <div class="alert alert-danger" role="alert">
                    No es posible anular facturas de dias posteriores!
                </div>
        @endif

    </div>

</div>

<script>
    window.addEventListener('factura-anulada-correctamente', () => {
        Swal.fire({
            icon: "success",
            title: "Factura anulada correctamente",
            showConfirmButton: false,
            timer: 1500
        });
        setTimeout(() => {
            location.reload();
        }, 1500);
    });

    Livewire.on('error', function(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
        });
    });
</script>
