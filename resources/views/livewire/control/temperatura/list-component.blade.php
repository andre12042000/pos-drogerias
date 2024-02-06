<div>

    <div> @include('includes.alert')</div>
    @include('popper::assets')
    <div class="card card-info  mt-2">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-5">
                    <h3>Historial de seguimiento control de temperatura</h3>
                </div>
                <div class="col-sm-7">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Ajusté el tamaño de la columna para que los input date queden al final -->
                            <div class="input-group mb-3 float-right">
                                <input type="date" class="form-control" placeholder="Desde" aria-label="Username"
                                    wire:model="desde">
                                    @error("desde") <span class="text-danger">{{ $message }}</span> @enderror
                                <span class="input-group-text">Hasta</span>
                                <input type="date" class="form-control" placeholder="Hasta" aria-label="Server"
                                    wire:model="hasta">
                                    @error("hasta") <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- Ajusté el tamaño de la columna para que los botones queden al final -->
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-outline-light ml-2" data-toggle="modal"
                                    data-target="#registrarSeguimientoTemperatura">
                                    Nuevo seguimiento <i class="las la-plus-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Lugar</th>
                        <th class="text-center">Temperatura</th>
                        <th class="text-center">Humedad</th>
                        <th class="text-center">Cadena de frio</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($seguimientos as $seguimiento)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($seguimiento->fecha)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($seguimiento->hora)->format('h:i A') }}</td>
                            <td>{{ $seguimiento->sitio->name }}</td>
                            <td class="text-center">{{ round($seguimiento->temperatura) }} °C</td>
                            <td class="text-center">{{ round($seguimiento->humedad) }}</td>
                            <td class="text-center">{{ round($seguimiento->cadena_frio) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <p>No se encontraron registros...</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
    @include('modals.temperatura.create')
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
