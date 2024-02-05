<div>

    <div> @include('includes.alert')</div>
    @include('popper::assets')
    <div class="card card-info  mt-2">
        <div class="card-header">

            <div class="row">
                <div class="col-sm-6">
                    <h3>Sitios con control de temperatura</h3>
                </div>


                <div class="col-sm-6">
                    <button type="button" class="btn btn-outline-light float-right ml-2" data-toggle="modal"
                        data-target="#crearSitioCadenaFrio">Nuevo sitio <i class="las la-plus-circle"></i></button>

                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Sitio</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sitios as $sitio)
                        <tr>
                            <td>{{ ucwords($sitio->name) }}</td>
                            <td>
                                @if ($sitio->status == 'ACTIVE')
                                    Activo
                                @else
                                    Inactivo
                                @endif
                            </td>

                            <td class="text-center">

                                {{-- <a @popper(Actualizar) class="btn btn-outline-success btn-sm" href="#" role="button"
                                data-toggle="modal" data-target="#categoryModal"
                                wire:click="sendData( {{ $category }} )"><i class="bi bi-pencil-square"></i></a>

                            <button @popper(Eliminar) class="btn btn-outline-danger btn-sm"
                                wire:click="destroy( {{ $category->id }} )"><i class="bi bi-trash3"></i></button> --}}
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="3">
                                <p>No se encontraron registros...</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
    @include('modals.sitios-temperatura.create')
</div>

<script>
    window.addEventListener('close-modal', event => {
        //alert('Hola mundo');
        $('#crearSitioCadenaFrio').hide();
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    })
</script>

<script>
    window.addEventListener('mostrar-registro-creado', event => {
                Swal.fire({
                    icon: "success",
                    title: "Registro creado correctamente",
                    showConfirmButton: false,
                    timer: 1500
                });

            })


</script>
