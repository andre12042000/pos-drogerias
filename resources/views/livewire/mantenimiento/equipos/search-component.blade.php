<div>
    @include('popper::assets')
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
            <div class="input-group col-5 float-right">

            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar equipo" aria-label="Username"
                            aria-describedby="basic-addon1"  wire:model = "buscar">
                            </div>
                                      <button type="button" class="btn-close " data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>
            </div>
            <div class="modal-body ">
                <table class="table table-striped" id="tabClients">
                    <thead>
                        <tr>
                            <th>Placa</th>
                            <th>Tipo Equipo</th>
                            <th>Modelo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($equipos as $equipo)
                        <tr>
                            <td>{{ ucwords($equipo->serial_placa) }}</td>
                            <td>{{ $equipo->tipoequipo->descripcion }} </td>
                            <td>{{ $equipo->modelo }}</td>
                            <td class="text-center"><i class="bi bi-check2-circle" style="cursor: pointer" wire:click="selectEquipo({{ $equipo }})" data-dismiss="modal"></i></td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="4">
                                <p>No se encontraron registros...</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="modal-footer mt-2">

            <nav class="" aria-label="">
                <ul class="pagination">
                  {{ $equipos->links() }}
                </ul>
              </nav>


                </div>
            </div>
        </div>
    </div>

</div>
