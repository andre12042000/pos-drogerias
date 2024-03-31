<div class="container">
<div class="row mt-3">

    <div class="col">
        <label>Tipo de Operación</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoOperacionOptions"
                id="tipoOperacionVenta" value="VENTA" checked wire:model.defer="tipo_operacion">
            <label class="form-check-label" for="tipoOperacionVenta">VENTA</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoOperacionOptions"
                id="tipoOperacionCredito" value="CREDITO" wire:model.defer="tipo_operacion">
            <label class="form-check-label" for="tipoOperacionCredito">CRÉDITO</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="tipoOperacionOptions"
                id="tipoOperacionCortesia" value="CORTESIA" wire:model.defer="tipo_operacion">
            <label class="form-check-label" for="tipoOperacionCortesia">CORTESÍA</label>
        </div>
    </div>

</div>
<div class="row mt-2">
    <div class="input-group" style="height: 38px; max-width: 100%">
        <select name="cajero" class="form-control" id="cajero">
            @foreach ($cajeros as $cajero)
                <option value="{{ $cajero->id }}">{{ ucwords($cajero->name) }}</option>
            @endforeach
        </select>
</div>
</div>
<div class="row mt-3">
    <div class="col">
        <div class="input-group" style="height: 38px; max-width: 100%;">
            <select class="form-select js-example-basic-single" name="cliente" id="cliente">
                    <option value="1">Consumidor final</option>
                @forelse ($clientes as $client)
                    <option value="{{ $client->id }}">{{ ucwords($client->name) }}</option>
                @empty
                <option value="">No hay registros disponibles...</option>
                @endforelse

            </select>
            <div class="input-group-append" id="button-addon4">
                <button class="btn btn-outline-secondary" type="button"data-toggle="modal"
                    data-target="#clientmodal"><i class="bi bi-plus-circle-fill"
                        style="cursor: pointer"></i></button>
            </div>
        </div>
        <div id="cambiarClienteHelp" class="text-danger" style="display: none;">
            ¡Es necesario cambiar de cliente!
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-6">
        <div class="form-floating">
            <select class="form-select" id="selectMetodoPago" name="metodoPagoSelect"
                aria-label="Floating label select example">
                @foreach ($metodos_pago as $metodo)
                    <option value="{{ $metodo->id }}"
                        @if ($metodo->id == '2') selected @endif>{{ $metodo->name }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Método de pago</label>
        </div>
      </div>
      <div class="col-6">
        <label for="exampleFormControlInput1" class="form-label">Imprimir recibo</label>
        <br>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="opcionRadio" id="opcionSi"
                value="1">
            <label class="form-check-label" for="opcionSi">Si</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="opcionRadio" id="opcionNo"
                value="0" checked>
            <label class="form-check-label" for="opcionNo">No</label>
        </div>
      </div>
</div>

<div class="row mt-4">
    <div class="col-6">

    </div>
    <div class="col-6">
        <div class="col columna-cuentas">
            <div class="costo-compra mt-3">
                <label>Subtotal:</label>
                <span class="subTotal" style="font-size: 1.5em;
                text-align: right;"></span>
            </div>
            <div class="costo-compra mt-3">
                <label>Descuento:</label>
                <div class="input-group mb-3">
                    <input type="number" class="form-control" aria-label="Text input with dropdown button" id="inputDescuento" disabled>
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <div class="form-check form-check-inline m-2">
                            <input class="form-check-input" type="radio" name="tipoDescuento" id="descuentoPorcentaje" value="porcentaje">
                            <label class="form-check-label" for="descuentoPorcentaje">Porcentaje (%)</label>
                        </div>
                      </li>
                      <li>
                        <div class="form-check form-check-inline m-2">
                            <input class="form-check-input" type="radio" name="tipoDescuento" id="descuentoValorFijo" value="valor_fijo">
                            <label class="form-check-label" for="descuentoValorFijo">Valor fijo ($)</label>
                        </div>
                      </li>

                    </ul>
                  </div>
            </div>

            <div class="costo-compra mt-3">
                <label>IVA:</label>
                <span class="iva" style="font-size: 1.5em;"></span>
            </div>
            <div class="costo-compra total mt-3">
                <label>Total:</label>
                <span class="TOTAL" style="font-size: 1.5em;"></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mt-4">
        <div class="d-grid">
            <button class="btn btn-primary btn-pagar" id="pagarBtn" type="button"
                disabled><strong>PAGAR</strong>
                <img class="loader" src="{{ asset('img/loading.gif') }}" alt="Cargando..."
                    width="30px;" style="display: none;" />
            </button>
        </div>
    </div>

</div>

</div>


<style>
    .costo-compra {
        display: flex; /* Utilizar flexbox */
        justify-content: space-between; /* Espacio entre los elementos */
        align-items: center; /* Alinear verticalmente al centro */
    }

    .costo-compra label {
        margin-right: 10px; /* Espacio entre el label y el valor */
    }
</style>

@section('js')
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();

            // Escucha el evento emitido por Livewire cuando se agrega un nuevo cliente
            window.livewire.on('ClientEvent', function(client) {
                // Agrega el nuevo cliente al select2
                var option = new Option(client.name, client.id, true, true);
                $('#cliente').append(option).trigger('change');

                // Opcional: Puedes seleccionar automáticamente el nuevo cliente
                // $('#cliente').val(client.id).trigger('change');

                // Opcional: Puedes enfocar y abrir el select2 para que el usuario pueda ver el nuevo cliente
                // $('#cliente').select2('open');
            });


        });
    </script>
@stop


