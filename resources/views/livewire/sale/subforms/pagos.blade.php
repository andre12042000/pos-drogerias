<div class="container">
<div class="row mt-4">

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
    </div>

</div>
<div class="row mt-4">
    <div class="col">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Cliente" id="client"
                aria-label="Recipient's username with two button addons"
                aria-describedby="button-addon4" value="{{ $client_name }}" disabled readonly>
            <div class="input-group-append" id="button-addon4">
                <button class="btn btn-outline-secondary" type="button"data-toggle="modal"
                    data-target="#searchclient"><i class="bi bi-search"
                        style="cursor: pointer"></i></button>
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

<div class="row mt-4">
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
                    <input type="text" class="form-control" aria-label="Text input with dropdown button" disabled>
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li><a class="dropdown-item" href="#">$</a></li>
                      <li><a class="dropdown-item" href="#">%</a></li>
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

<script>

let subTotal = 0;
let iva = 0;
let TOTAL = 0;

// Asignar los valores a los elementos HTML
document.querySelector('.subTotal').textContent = `${subTotal}`;
document.querySelector('.iva').textContent = `${iva}`;
document.querySelector('.TOTAL').textContent = `${TOTAL}`;


</script>

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


