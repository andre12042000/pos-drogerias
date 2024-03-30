<div>
    <div> @include('includes.alert')</div>
    @include('popper::assets')
    <div class="card card-info mt-2">
        <div class="card-header mb-4">
            <div class="row">
                <div class="col-sm-10">
                    <h3>Detalles del cliente</h3>
                </div>
                <div class="col-sm-2 text-end float-end">


                        <button href="{{ route('terceros.client') }}" type="button" class="btn btn-outline-light  ml-2"><i class="bi bi-arrow-left-circle"></i> Atrás </button>


                </div>
            </div>
        </div>

        <div class="row" x-data="{ activeTab: 'creditos' }">
            <div class="col-lg-4">
                <h4 class="text-center"><strong>Información del Cliente</strong></h4>
                <ul class="mt-5">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <label class="form-control-label" for="nombre"><strong>Nombre</strong></label>
                        <p class="text-bold">{{ mb_strtoupper($cliente->name) }}</p>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <label class="form-control-label" for="nombre"><strong>Documento</strong></label>
                        <p>{{ $cliente->type_document }} {{ $cliente->number_document }}</p>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <label class="form-control-label" for="num_compra"><strong>Teléfono</strong></label>
                        <p> {{ $cliente->phone }} </p>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <label class="form-control-label" for="num_compra"><strong>Email</strong></label>
                        <p> {{ $cliente->email }} </p>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <label class="form-control-label" for="num_compra"><strong>Fecha registro</strong></label>
                        <p>{{ $cliente->created_at->format('d/m/Y H:i:s') }}</p>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <label class="form-control-label" for="num_compra"><strong>DEUDA</strong></label>

                        @if ($cliente->deuda > 0)
                            <p class="text-danger"><strong>$ {{ number_format($cliente->deuda, 0) }}</strong></p>
                        @else
                            <p><strong>$ {{ number_format($cliente->deuda, 0) }}</strong></p>
                        @endif
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-grid gap-2 col-12 mx-auto">
                            <button class="btn btn-outline-primary" type="button" @click="activeTab = 'creditos'">Historial
                                Créditos</button>
                            <button class="btn btn-outline-success" type="button" @click="activeTab = 'pagos'">HistoriaPago Créditos</button>
                            <button class="btn btn-outline-secondary" type="button" @click="activeTab = 'ventas'">Historia
                                    Compras</button>
                        </div>
                    </li>



                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div id="selectedRowsDiv" style="display: none;" class="col-lg-12 mt-8">
                            <h4 class="text-center mt-4 mb-4"><strong>Pagar o abonar</strong></h4>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-control-label" for="deuda"><strong>DEUDA</strong></label>
                                </div>
                                <div class="col">
                                    <p id="deuda" class="text-end"></p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-control-label" for="metodo_pago"><strong>METODO DE
                                            PAGO</strong></label>
                                </div>
                                <div class="col">
                                    <select class="form-select" aria-label="Default select example" id="metodo_pago">
                                        <option value="" selected>Seleccione un método</option>
                                        @foreach ($metodos_pago as $metodo)
                                            <option value="{{ $metodo->id }}">{{ $metodo->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-control-label" for="select_tipo_pago"><strong>PAGO
                                            COMPLETO</strong></label>
                                </div>
                                <div class="col">
                                    <select class="form-select" aria-label="Default select example"
                                        id="select_tipo_pago">
                                        <option value="" selected>Seleccione una opción</option>
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label class="form-control-label" for="num_compra"><strong>ABONAR</strong></label>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <input type="text" class="form-control text-end" id="valor_abonado"
                                            placeholder="Valor a abonar" autofocus autocomplete="nope">
                                    </div>
                                </div>
                                <div id="mensajeError" class="text-danger"></div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label class="form-control-label" for="num_compra"><strong>SALDO</strong></label>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <p id="saldo" class="text-end"></p>
                                    </div>
                                </div>
                            </div>





                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-success btn-lg" type="button"
                                    id="btnPagar"><strong>PAGAR</strong></button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="col-lg-1"></div>

            <div class="col-lg-7">

                <div class="table-responsive" x-show="activeTab === 'creditos'">
                    <h4 class="text-center"><strong> Historial compras a crédito</strong></h4>

                    <table id="detalles" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-right">Código</th>
                                <th class="text-center">Valor</th>
                                <th class="text-right">Abono</th>
                                <th class="text-right"> Saldo</th>
                                <th class="text-center"> Estado</th>
                                <th class="text-center"> Fecha de compra</th>
                                <th>Acciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($credits as $credit)
                                <tr data-id="{{ $credit->id }}" data-valor="{{ $credit->valor }}"
                                    data-abono="{{ $credit->abono }}" data-saldo="{{ $credit->saldo }}"
                                    data-created-at="{{ $credit->created_at }}" data-sale="{{ $credit->sale_id }}">
                                    <!-- ... Resto de las celdas ... -->
                                    <td><input class="form-check-input" type="checkbox"
                                            data-id="{{ $credit->id }}" id="deudaId"
                                            {{ $credit->saldo == 0 ? 'disabled' : '' }}
                                            data-bs-toggle="{{ $credit->saldo == 0 ? 'tooltip' : '' }}"
                                            data-bs-placement="top"
                                            title="{{ $credit->saldo == 0 ? 'Esta deuda ya ha sido pagada' : '' }}">
                                    </td>
                                    <td class="text-center"> {{ $credit->sale->full_nro }} </td>
                                    <td class="text-end">$ {{ number_format($credit->valor, 0) }} </td>
                                    <td class="text-end">$ {{ number_format($credit->abono, 0) }} </td>
                                    <td class="text-end">$ {{ number_format($credit->saldo, 0) }} </td>
                                    <td class="text-center">
                                        @if ($credit->active == true)
                                            <span class="badge bg-danger">Activo</span>
                                        @else
                                            <span class="badge bg-light text-dark">Pagado</span>
                                        @endif
                                    </td>
                                    <td class="text-end">{{ date('d/m/Y h:i', strtotime($credit->created_at)) }} </td>
                                    <td>
                                        <a href="{{ route('ventas.pos.details', $credit->sale_id) }}" title="Detalles de factura" class="mr-2" style="text-decoration: none;">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('ventas.pos.imprimir.recibo', $credit->sale_id) }}" title="Detalles de factura" class="mr-2" style="text-decoration: none;">
                                            <i class="bi bi-printer"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>


                    </table>
                </div>

                <div x-show="activeTab === 'ventas'">
                    @include('livewire.client.includes.historialcompras')
                </div>
                <div x-show="activeTab === 'pagos'">
                    @include('livewire.client.includes.pagoscreditos')
                </div>
            </div>
        </div>

    </div>

</div>

@section('title', 'Detalles del Cliente')




<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.form-check-input');
        const deudaElement = document.getElementById('deuda');
        const saldoElement = document.getElementById('saldo');
        const valorAbonadoInput = document.getElementById('valor_abonado');
        const selectedRowsDiv = document.getElementById('selectedRowsDiv');
        const btnPagar = document.getElementById('btnPagar');
        const errorMensaje = document.getElementById('mensajeError');
        const tipoPagoSelect = document.getElementById('select_tipo_pago');
        const metodoPagoSelect = document.getElementById('metodo_pago');

        let selectedRowsData = [];

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', actualizarSaldo);
        });

        tipoPagoSelect.addEventListener('change', function() {

            let deudaActualizada = actualizarSaldo();

            if (tipoPagoSelect.value === 'Si') {
                // Si el tipo de pago es 'Si', toma el valor de deudaElement
                valorAbonadoInput.value = deudaActualizada;
                valorAbonadoInput.disabled = true; // Deshabilita el input
                saldoElement.textContent = 0;
            } else {
                // Si el tipo de pago es 'No', habilita el input para el valor abonado
                valorAbonadoInput.value = ''; // Puedes ajustar esto según tu lógica
                valorAbonadoInput.disabled = false; // Habilita el input

                saldoElement.textContent = deudaActualizada;
                valorAbonadoInput.addEventListener('input', function() {

                    this.value = this.value.replace(/[^0-9]/g,
                        ''); // Elimina caracteres no numéricos
                    let deudaActualizada = actualizarSaldo();

                });
            }
        });

        btnPagar.addEventListener('click', function() {

            let errores = [];
            if (metodoPagoSelect.value === '') {
                // Muestra un mensaje de error si alguno de los campos está vacío
                metodoPagoSelect.classList.add('is-invalid');
                errores.push('Por favor, selecciona un método de pago.');
            } else {
                metodoPagoSelect.classList.remove('is-invalid');
            }

            if (tipoPagoSelect.value === '') {
                tipoPagoSelect.classList.add('is-invalid');
                errores.push('Por favor, selecciona un tipo de pago.');
            } else {
                tipoPagoSelect.classList.remove('is-invalid');
            }

            if (tipoPagoSelect.value === 'No') {
                // Verifica que valorAbonadoInput.value sea un número mayor a 0 y menor o igual a la deuda
                let deudaActualizada = actualizarSaldo();

                const deudaNumero = parseFloat(deudaActualizada.replace(/[^\d.]/g, ''));

                if (valorAbonadoInput.value <= 0 || valorAbonadoInput.value > deudaNumero) {
                    valorAbonadoInput.classList.add('is-invalid');
                    errores.push('El valor abonado debe ser mayor a 0 y menor o igual a la deuda.');
                } else {
                    valorAbonadoInput.classList.remove('is-invalid');
                }
            }

            if (errores.length > 0) {
                // Muestra los mensajes de error
                alert(errores.join('\n'));
                return;
            } else {
                //Si selecciona que tipo de pago completo, entonces toma el valor deudaNumero y es el valor pagado
                //en el caso que tipo de pago no sea completo, es decir abono, entonces si trae el valor de valorAbonadoInput
                if (tipoPagoSelect.value === 'No') {
                    var valorPagado = valorAbonadoInput.value;
                } else {
                    let deudaActualizada = actualizarSaldo();
                    const deudaNumero = parseFloat(deudaActualizada.replace(/[^\d.]/g, ''));
                    var valorPagado = deudaNumero;
                }

                Livewire.emit('realizarPagoTotalEvent', selectedRowsData, valorPagado, metodoPagoSelect
                    .value);
                errorMensaje.textContent = '';
                // Aquí podrías enviar el formulario o realizar otras acciones
            }


            // Restablece el mensaje de error




        });

        function actualizarSaldo() {
            selectedRowsData = [];

            let totalSaldo = 0;

            checkboxes.forEach(function(cb) {
                if (cb.checked) {
                    const row = cb.closest('tr');
                    const saldoAttribute = row.getAttribute('data-saldo');
                    const saldo = parseFloat(saldoAttribute) || (saldoAttribute === '0' ? 0 : 0);
                    totalSaldo += saldo;

                    const rowData = {
                        id: row.getAttribute('data-id'),
                        valor: row.getAttribute('data-valor'),
                        abono: row.getAttribute('data-abono'),
                        saldo: row.getAttribute('data-saldo'),
                        createdAt: row.getAttribute('data-created-at'),
                        venta: row.getAttribute('data-sale'),
                    };
                    selectedRowsData.push(rowData);
                }
            });

            if (selectedRowsData.length > 0) {
                selectedRowsDiv.style.display = 'block';
                deudaElement.textContent = `$${totalSaldo.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}`;

                // Obtiene el valor pagado por el usuario y asegura que sea un número
                var valorPagado = parseFloat(valorAbonadoInput.value) || 0;

                // Calcula el nuevo saldo
                var nuevoSaldo = totalSaldo - valorPagado;

                // Actualiza la etiqueta de saldo
                saldoElement.textContent = `$${nuevoSaldo.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}`;

                // Devuelve el valor actualizado de deudaElement
                return `$${totalSaldo.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}`;
            } else {
                selectedRowsDiv.style.display = 'none';
                return ''; // Devuelve un valor vacío en caso de que no haya datos seleccionados
            }
        }
    });
</script>

<script>
    window.addEventListener('pago_generado', event => {
        const numeroVenta = event.detail.pago;
        Swal.fire({
            icon: "success",
            title: "Pago realizado correctamente",
            text: `Recibo Nro: ${numeroVenta}`,
            showConfirmButton: false,
            timer: 1500
        });
        setTimeout(() => {
            location.reload();
        }, 1500);

    })
</script>

<script>
    window.addEventListener('error_alert', event => {
        const error = event.detail.error;
        Swal.fire({
            icon: "error",
            title: "Ops!, ocurrio un error, no es posible actualizar los datos",
            text: `Error: ${error}`,
            showConfirmButton: false,
        });
    })
</script>
