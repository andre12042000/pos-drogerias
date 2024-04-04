<div>
    <div> @include('includes.alert')</div>
    @include('popper::assets')
    <div class="card card-info mt-2">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Roles de usuario</h3>
                </div>
                <div class="col-sm-6">

                    <button type="button" class="btn btn-outline-light float-right ml-2" data-toggle="modal" data-target="#rolemodal" >Nuevo rol <i class="las la-plus-circle"></i></button>

                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="tabCategorias">
                <thead>
                    <tr>
                        <th>Nombre</th>


                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $rol)
                    <tr>
                        <td>{{ ucwords($rol->name) }}</td>

                        <td class="text-center">

                            <a @popper(Editar) class="btn btn-outline-success btn-sm" href="#" role="button" data-toggle="modal" data-target="#rolemodal" wire:click="sendData( {{ $rol }} )"><i class="bi bi-pencil-square"></i></a>

                            <button @popper(Eliminar) class="btn btn-outline-danger btn-sm" wire:click="destroy( {{ $rol->id }} )"><i class="bi bi-trash3"></i></button>


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
    </div>
</div>
