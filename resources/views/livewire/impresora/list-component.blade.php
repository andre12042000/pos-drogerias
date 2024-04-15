<div>
    <div> @include('includes.alert')</div>
    @include('popper::assets')
    <div class="card card-info  mt-2">
        <div class="card-header">

            <div class="row">
                <div class="col-sm-6">
                    <h3>Impresora</h3>
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
                        <input type="text" class="form-control" placeholder="Buscar Impresora" aria-label="Username"
                            aria-describedby="basic-addon1" wire:model="buscar">

                        <button type="button" class="btn btn-outline-light float-right ml-2" data-toggle="modal"
                            data-target="#editarImpresoraModal">Nueva Impresora <i class="las la-plus-circle"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre Impresora</th>
                        <th>Nombre PC</th>

                        <th>Predeterminda</th>

                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($impresoras as $impresora)
                    <tr>
                        <td>{{ ucwords($impresora->nombre) }}</td>
                        <td>{{ ucwords($impresora->name_pc) }}</td>
                        <td>@if($impresora->predeterminada == 1 ) <span class="badge bg-success">ACTIVA</span>  @else <span class="badge bg-danger">INACTIVA</span>  @endif</td>

                        <td class="text-center">

                            <a @popper(Editar) class="btn btn-outline-success btn-sm" href="#" role="button"
                                data-toggle="modal" data-target="#editarImpresoraModal"
                                wire:click="sendData( {{ $impresora }} )"><i class="bi bi-pencil-square"></i></a>

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
                    {{ $impresoras->links() }}
                </ul>
            </nav>

        </div>
    </div>
</div>
<script>
    window.addEventListener('close-modal', event => {
             //alert('Hola mundo');
                $('#editarImpresoraModal').hide();
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            })
</script>
