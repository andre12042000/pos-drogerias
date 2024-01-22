<div>
    <div> @include('includes.alert')</div>
    @include('popper::assets')
    <div class="card card-info  mt-2">
        <div class="card-header">

            <div class="row">
                <div class="col-sm-6">
                    <h3>Presentación</h3>
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
                        <input type="text" class="form-control" placeholder="Buscar categoría" aria-label="Username"
                            aria-describedby="basic-addon1" wire:model="buscar">

                        <button type="button" class="btn btn-outline-light float-right ml-2" data-toggle="modal"
                            data-target="#categoryModal">Nueva presentación <i class="las la-plus-circle"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Disponible caja</th>
                        <th>Disponible blister</th>
                        <th>Disponible unidad</th>
                        <th>% Caja</th>
                        <th>% Blister</th>
                        <th>% Unidad</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($presentaciones as $presentacion)
                    <tr>
                        <td>{{ ucwords($presentacion->name) }}</td>
                        <td>@if($presentacion->disponible_caja == true)
                            Si
                        @else
                            No
                        @endif</td>

                        <td>@if($presentacion->disponible_blister == true)
                            Si
                        @else
                            No
                        @endif</td>

                        <td>@if($presentacion->disponible_unidad == true)
                            Si
                        @else
                            No
                        @endif</td>
                        <td>{{ $presentacion->por_caja }} %</td>
                        <td>{{ $presentacion->por_blister }} %</td>
                        <td>{{ $presentacion->por_unidad }} %</td>
                        <td>@if($presentacion->status == 'ACTIVE')
                            <span class="badge bg-success">ACTIVO</span>
                        @else
                        <span class="badge bg-danger">DESHABILITADO</span>
                        @endif</td>
                        <td class="text-center">

                            <a @popper(Actualizar) class="btn btn-outline-success btn-sm" href="#" role="button"
                                data-toggle="modal" data-target="#presentacionModal"
                                wire:click="sendData( {{ $presentacion }} )"><i class="bi bi-pencil-square"></i></a>

                            <button @popper(Eliminar) class="btn btn-outline-danger btn-sm"
                                wire:click="destroy( {{ $presentacion->id }} )"><i class="bi bi-trash3"></i></button>
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
                    {{ $presentaciones->links() }}
                </ul>
            </nav>

        </div>
    </div>
</div>
@include('modals.presentacion.createpresentacion')

