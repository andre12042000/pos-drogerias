<div>
    <div> @include('includes.alert')</div>
    @include('popper::assets')
    <div class="card card-info  mt-2">
        <div class="card-header">

            <div class="row">
                <div class="col-sm-6">
                    <h3>Ubicación</h3>
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
                        <input type="text" class="form-control" placeholder="Buscar laboratorio" aria-label="Username"
                            aria-describedby="basic-addon1" wire:model="buscar">

                        <button type="button" class="btn btn-outline-light float-right ml-2" data-toggle="modal"
                            data-target="#ubiModal">Nuevo ubicación <i class="las la-plus-circle"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>

                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ubicaciones as $ubi)
                    <tr>
                        <td>{{ ucwords($ubi->name) }}</td>


                        <td>@if($ubi->status == 'ACTIVE')
                            <span class="badge bg-success">ACTIVO</span>
                        @else
                        <span class="badge bg-danger">DESHABILITADO</span>
                        @endif</td>
                        <td class="text-center">

                            <a @popper(Actualizar) class="btn btn-outline-success btn-sm" href="#" role="button"
                                data-toggle="modal" data-target="#ubiModal"
                                wire:click="sendData( {{ $ubi }} )"><i class="bi bi-pencil-square"></i></a>

                            <button @popper(Eliminar) class="btn btn-outline-danger btn-sm"
                                wire:click="destroy( {{ $ubi->id }} )"><i class="bi bi-trash3"></i></button>
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
                    {{ $ubicaciones->links() }}
                </ul>
            </nav>

        </div>
    </div>
</div>
@include('modals.ubicacion.create')
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
