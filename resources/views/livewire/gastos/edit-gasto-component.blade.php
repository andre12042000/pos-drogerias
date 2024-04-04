<div>
    @section('title', 'Detalles Del Gasto')

    @include('popper::assets')
    <div class="card card-info mt-1">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-8">
                    <h3>Detalles del gasto</h3>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-outline-light btn-sm mt-1 float-right" onclick="redirigir()"><i
                            class='bi bi-arrow-left-circle'></i> Atrás</button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-4">

                        <div class="card">
                            <ul class="list-group list-group-flush">

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Fecha de
                                            compra</strong></label>
                                    <p> {{ \Carbon\Carbon::parse($gasto->created_at)->format('d M Y') }}</p>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Método de
                                            pago</strong></label>
                                    <p>{{ ucwords(strtolower($gasto->metodopago->name)) }}</p>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Usuario</strong></label>
                                    <p>{{ ucwords($gasto->user->name) }}</p>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label"
                                        for="num_compra"><strong>Categoría</strong></label>
                                    <p>{{ ucwords($gasto->categoria->name) }}</p>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Detalles</strong></label>
                                    <p>{{ ucwords($gasto->descripcion) }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Total</strong></label>
                                    <h3>$ {{ number_format($gasto->total, 0) }}</h3>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Estado</strong></label>
                                    <p>
                                        @if ($gasto->status == 'APLICADO')
                                            <span class="badge bg-success">APLICADO</span>
                                        @else
                                            <span class="badge bg-secondary">BORRADOR</span>
                                        @endif
                                    </p>
                                </li>

                            </ul>

                            <div class="card-footer">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-lg" wire:click="confirmacionaplicar"
                                        @if ($gasto->status == 'APLICADO') disabled @endif> Finalizar registro
                                        <div wire:loading wire:target="confirmacionaplicar">
                                            <img src="{{ asset('img/loading.gif') }}" width="20px" class="img-fluid"
                                                alt="">
                                        </div>
                                    </button>
                                </div>
                                @error('gastoDetailsError')
                                    <div class="text-center mt-2">
                                        <span class="text-danger">¡Se requiere al menos un producto o servicio en los detalles de
                                            compra.!</span>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        @if (!is_null($gasto->picture))

                            <div class="mb-3 text-center mt-4">
                                <img  src="{!! Config::get('app.URL') !!}/storage/{{ $gasto->picture }}" alt="">
                            </div>

                        @endif


                    </div>

                    <div class="card col-8">
                        <div class="card-header">
                            <div class="container">
                                <div class="row">
                                    <h4 class="card-title mb-0 mt-2 mb-4"><strong>Agregar detalle</strong></h4>
                                </div>

                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="descripcion"
                                                placeholder="name@example.com" autocomplete="off" autofocus @if ($gasto->status == 'APLICADO') disabled @endif>
                                            <label for="floatingInput">Descripción</label>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control text-center" id="cantidad"
                                                name="cantidad" placeholder="name@example.com" value="1" min="1"
                                                autocomplete="nope" @if ($gasto->status == 'APLICADO') disabled @endif>
                                            <label for="floatingInput">Cantidad</label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control text-end" id="precio_unitario"
                                                name="precio_unitario" placeholder="name@example.com"
                                                autocomplete="nope" min="1000" @if ($gasto->status == 'APLICADO') disabled @endif>
                                            <label for="floatingInput">Precio Unit.</label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control text-end" id="total"
                                                name="total" placeholder="name@example.com" autocomplete="nope"
                                                disabled>
                                            <label for="floatingInput">Total</label>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <button type="button" id="enviar"  name="enviar" class="btn btn-outline-secondary mt-1"
                                            style="width: 50px; height: 50px;" onclick="enviarLivewire()" @if ($gasto->status == 'APLICADO') disabled @endif><i
                                                class="bi bi-plus-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="card-body">
                            <h4 class="card-title mb-0 mt-2 mb-4"><strong>Detalles de compra</strong></h4>
                            <div class="table-responsive col-md-12">
                                <table id="detalles" class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Descripción</th>
                                            <th>Cantidad</th>
                                            <th>Precio Unitario</th>
                                            <th class="text-right">Total</th>
                                            <th class="text-right"> </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse ($detalles as $detalle)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $detalle->descripcion }}</td>
                                                <td class="text-center">{{ $detalle->cantidad }}</td>
                                                <td class="text-right">$ {{ number_format($detalle->precio_unitario, 0) }}</td>
                                                <td class="text-right">$ {{ number_format($detalle->total, 0) }}</td>
                                                <td class="text-right">
                                                    @if ($gasto->status == 'APLICADO')
                                                       <a class="btn btn-outline-danger btn-sm disabled" disabled ><i class="bi bi-trash3" style="font-size: 18px; cursor: not-allowed;"></i></a>
                                                    @else
                                                    <a class="btn btn-outline-danger btn-sm " title="Eliminar" wire:click="destroy( {{ $detalle->id }} )" ><i class="bi bi-trash3" style="font-size: 18px; cursor: pointer;" ></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="mb-4 mt-4">
                                                    <p class="text-center">No hay registros disponibles...</p>
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>

                                    <tfoot>

                                        <tr>
                                            <th colspan="4">
                                                <h3 align="right">TOTAL:</h3>
                                            </th>
                                            <th colspan="2">
                                                <h3 align="right">$ {{ number_format($totalfull, 0) }}</h3>
                                            </th>
                                        </tr>

                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>










    @push('js')
    <script>
        // Obtener los elementos del DOM
        const descripcionInput = document.getElementById('descripcion');
        const cantidadInput = document.getElementById('cantidad');
        const precioUnitarioInput = document.getElementById('precio_unitario');
        const totalInput = document.getElementById('total');

        function enviarLivewire() {
            // Validar que los campos no estén vacíos
            if (descripcionInput.value.trim() === '' || cantidadInput.value.trim() === '' || precioUnitarioInput.value.trim() === '') {
                alert('Por favor, complete todos los campos antes de enviar.');
                return;
            }

            // Envío a Livewire
            Livewire.emit('formularioEnviadoEvent', {
                descripcion: descripcionInput.value.trim(),
                cantidad: parseFloat(cantidadInput.value),
                precioUnitario: parseFloat(precioUnitarioInput.value),
                total: parseFloat(totalInput.value)
            });

            // Limpiar los valores de los inputs después de enviar
            descripcionInput.value = '';
            cantidadInput.value = '1'; // Puedes establecer el valor predeterminado que desees
            precioUnitarioInput.value = '';
            totalInput.value = '';
        }

        // Función para calcular el total
        function calcularTotal() {
            const cantidad = parseFloat(cantidadInput.value);
            const precioUnitario = parseFloat(precioUnitarioInput.value);

            // Verificar si la cantidad y el precio unitario son números válidos
            if (!isNaN(cantidad) && !isNaN(precioUnitario)) {
                const total = cantidad * precioUnitario;
                totalInput.value = total.toFixed(0); // Redondear a 2 decimales
            } else {
                totalInput.value = ''; // Si los valores no son válidos, dejar el total en blanco
            }
        }

        // Agregar eventos de cambio a los campos de cantidad y precio unitario
        cantidadInput.addEventListener('input', calcularTotal);
        precioUnitarioInput.addEventListener('input', calcularTotal);
    </script>


        <script>
            function redirigir() {
                location.replace('{!! Config::get('app.URL') !!}/gastos');
            }
        </script>


        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



        <script>
            window.addEventListener('confirmar_cierre_gasto', event => {

                Swal.fire({
                    title: 'Cerrar registro de gasto',
                    text: 'Desea finalizar este registro',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, registrar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('confirmarEventGasto')
                    }
                })
            })
        </script>
    @endpush
</div>

<script>
    window.addEventListener('gasto-registrado', event => {
        Swal.fire({
            icon: "success",
            title: "Gasto registrado correctamente",
            text: "Haz registrado correctamente el gasto",
            showConfirmButton: false,
            timer: 3000
        });
        setTimeout(() => {
            // Obtener la URL de la ruta usando su nombre
            const rutaDeseadaUrl = '{{ route('gastos.list') }}';

            // Redirigir a la ruta deseada
            window.location.href = rutaDeseadaUrl;
        }, 3000);

    })
</script>

<script>
    window.addEventListener('error', event => {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ocurrio un error!",
        });
    })
</script>
