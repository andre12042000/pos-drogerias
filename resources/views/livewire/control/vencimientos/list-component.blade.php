<div>
    @section('title', 'Vencimientos')

    @section('content_header')

        <div class="row float-right mr-1">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Control</li>

                    <li class="breadcrumb-item active" aria-current="page">Vencimientos</li>
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
                <div class="col-sm-7">
                    <h3>Vencimientos</h3>
                </div>


                <div class="col-sm-5 float-right bg-secon">
                    <div class="input-group float-right">
                        <select wire:model="status" class="form-select col-sm-2 mr-2"
                            aria-label="Default select example">
                            <option value="ACTIVE"> Activos </option>
                            <option value="DESACTIVE">Deshabilitados</option>

                        </select>
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control mr-2" placeholder="Buscar lote" aria-label="Username"
                            aria-describedby="basic-addon1" wire:model="buscar">

                        <div class="btn-group " role="group" aria-label="Basic mixed styles example">
                            <button type="button" title="Filtar por 3 meses" wire:click="filtrotres"
                                class="btn btn-danger btn-lg"></button>
                            <button type="button" title="Filtar por 6 meses" wire:click="filtrosesis"
                                class="btn btn-warning btn-lg"></button>
                            <button type="button" title="Filtar por +1 año" wire:click="filtromasyeard"
                                class="btn btn-success btn-lg"></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Lote</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vencimientos as $ven)
                        <tr>
                            <td>{{ $ven->product->code }} </td>
                            <td>{{ ucwords($ven->product->name) }}</td>
                            <td>{{ $ven->lote }}</td>
                            <td>{{ $ven->cantidad_ingresada - $ven->cantidad_vendida }} </td>
                            <td>
                                @if ($ven->fecha_vencimiento > $seis)
                                    <span class="badge bg-success">{{ $ven->fecha_vencimiento }}</span>
                                @elseif ($ven->fecha_vencimiento > $tres || $ven->fecha_vencimiento > $seis)
                                    <span class="badge bg-warning">{{ $ven->fecha_vencimiento }}</span>
                                @elseif($ven->fecha_vencimiento > $hoy || $ven->fecha_vencimiento > $tres)
                                    <span class="badge bg-danger">{{ $ven->fecha_vencimiento }}</span>
                                    @else
                                    <span class="badge bg-secondary">{{ $ven->fecha_vencimiento }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($ven->status == 'ACTIVE')
                                    <span class="badge bg-success">ACTIVO</span>
                                @else
                                    <span class="badge bg-danger">DESHABILITADO</span>
                                @endif
                            </td>
                            <td class="text-center">

                                <a class="btn btn-outline-danger" title="Eliminar vencimiento"
                                    onclick="confirmar_eliminacion()" wire:click="data({{ $ven->id }})"><i
                                        class="bi bi-slash-circle"></i></a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="2">
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
                    {{ $vencimientos->links() }}
                </ul>
            </nav>

        </div>
    </div>
</div>
@include('modals.presentacion.createpresentacion')
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

<script>
    function confirmar_eliminacion() {

        Swal.fire({
            title: '¿Seguro desea notificación esta alerta?',
            text: "¡Recuerda que puedes revertir los cambios!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#17A2B8',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, eliminar notificación!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('EliminarVencimientoEvent')
                /* Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
                ) */
            }
        })
    }
</script>

<script>
    window.addEventListener('confirmacion', event => {
        Swal.fire({
            position: "center",
            icon: "success",
            title: "Notificación actualizada exitosamente!",
            showConfirmButton: false,
            timer: 1500
        });
    });
</script>
