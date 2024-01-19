<div class="py-5">

    <div > @include('includes.alert')</div>
    @include('popper::assets')
    <div class="card card-info mt-2">
        <div class="card-header">
        <div class="row">
                    <div class="col-sm-7">
                    <h3>Equipos</h3>
                    </div>
                    <div class="col-sm-5">
                    <div class="input-group float-right">
                    <select  wire:model="cantidad_registros" class="form-select col-sm-2 mr-2" aria-label="Default select example">
                            <option value="10"> 10 </option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                    </select>
                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Buscar equipo" aria-label="Username"
                                aria-describedby="basic-addon1"  wire:model = "buscar">
                                <button type="button" class="btn btn-outline-light float-right ml-2" data-toggle="modal" data-target="#modalEquipos" >Nuevo equipo <i class="las la-plus-circle"></i></button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="tabClientes">
                    <thead>
                        <tr>
                            <th>Referencia</th>
                            <th>Color</th>
                            <th>Modelo</th>
                            <th>Marca</th>
                            <th>Tipo Equipo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($equipos as $equipo)
                            <tr>
                                <td>{{ ucwords($equipo->referencia) }}</td>
                                <td>{{ ucwords($equipo->color) }}</td>
                                <td>{{ ucwords($equipo->modelo) }}</td>
                                <td>{{ ucwords($equipo->brand->name) }}</td>
                                <td>{{ ucwords($equipo->tipoequipo->descripcion) }}</td>
                                <td class="text-center">
                                <a @popper(Actualizar) class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#modalEquipos"  wire:click="sendData( {{ $equipo }} )" role="button"><i class="bi bi-pencil-square"></i></a>
                                        <button  @popper(Eliminar) class="btn btn-outline-danger btn-sm" wire:click="destroy( {{ $equipo->id }} )"><i class="bi bi-trash3"></i></button>

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
            <div class="card-footer ">
                <nav class="" aria-label="">
                    <ul class="pagination">
                      {{ $equipos->links() }}
                    </ul>
                  </nav>

            </div>
        </div>
    </div>

    @include('modals.mantenimiento.equipos.equipo')
