<div>
@include('popper::assets')
<div id="alerts" > @include('includes.alert')</div>
    <div class="card">
        <div class="card-header">

            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                @if ($empresa->modal_aut_produc == 1)

                                @endif
                                <i class="bi bi-search mr-3 mt-2" style="cursor: pointer" data-toggle="modal" data-target="#searchproduct"></i>
                            </div>
                            <input type="text" class="form-control @if($error_search) is-invalid @endif" id="inlineFormInputGroup"
                                placeholder="Buscar producto por código" wire:model.lazy ="codigo_de_producto" wire:keydown.enter="searchProductCode" autofocus autocomplete="disabled"> <!--Buscador por código -->
                    </div>
                    @error('codigo_de_producto')
                                <span class="text-danger">{{ $message }}</span>
                    @enderror

            </div>
                </div>
                <div class="row">
                    <div class="col ml-4 mt-1">
                        <Label>Factura electrónica</Label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" disabled>
                            <label class="form-check-label" for="inlineRadio1">Si</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="0" disabled checked>
                            <label class="form-check-label" for="inlineRadio2">No</label>
                          </div>

                    </div>
                   <div class="col">

                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-3 col-form-label">Proceso</label>
                        <div class="col-sm-9">
                            <select class="form-select" aria-label="Default select example" disabled>
                                <option selected>Venta</option>
                                <option value="Venta">Venta</option>
                                <option value="Credito">Venta credito</option>
                                <option value="Consumo Interno">Consumo interno</option>
                              </select>
                        </div>
                      </div>
                    </div>
                    <div class="col">

                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cliente"
                                aria-label="Recipient's username with two button addons"
                                aria-describedby="button-addon4" value="{{ $client_name }}" disabled readonly>
                            <div class="input-group-append" id="button-addon4">
                                <button class="btn btn-outline-secondary" type="button"data-toggle="modal"
                                        data-target="#searchclient"><i class="bi bi-search"
                                        style="cursor: pointer" ></i></button>
                                <button class="btn btn-outline-secondary" type="button"data-toggle="modal" data-target="#clientmodal"><i
                                        class="bi bi-plus-circle-fill" style="cursor: pointer" ></i></button>
                            </div>
                        </div>


                    </div>
                </div>
            </div>



        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive-md">
                <table class="table" id="tablaProductos" wire:ignore>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Código</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Forma</th>
                            <th scope="col">Precio Unit.</th>
                            <th scope="col">Cant.</th>
                            <th scope="col">Descuento</th>
                            <th scope="col">Total</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>
                    <tbody style="height: 270px;">

                    </tbody>

                </table>
            </div>

        </div>
        <div class="card-footer">

            <div class="container">
                <div class="row">
                  <div class="col-8">

                  </div>
                  <div class="col-4">
                    <div class="costo-compra">
                        <label>Subtotal:</label>
                        <span class="valor">$150.00</span>
                    </div>
                    <div class="costo-compra">
                        <label>IVA:</label>
                        <span class="valor">$24.00</span>
                    </div>
                    <div class="costo-compra total">
                        <label style=" font-size: 30px;
                        font-weight: bold;">Total:</label>
                        <span class="valor">$174.00</span>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-pagar" id="btn-pagar" type="button"><strong>Pagar</strong></button>
                    </div>
                </div>
                </div>
              </div>
        </div>

            @include('modals.sale.forma_cantidad')

<script src="{{ asset('js/ventas/procesoVenta.js') }}"></script>
 <script>
        // Verificar la condición
        @if ($empresa->modal_aut_produc == "1")
            $(document).ready(function() {
                // Abrir el modal
                $('#searchproduct').modal('show');
            });
        @endif
</script>
    </div>

</div>


@section('css')
<style>

    .ocultar-columna-id {
        display: none;
    }

    .table-responsive-md {
    max-height: 400px; /* Establece la altura máxima que prefieras */
    overflow-y: auto; /* Añade un scrollbar vertical cuando sea necesario */
    }

    /* Estilo del botón de pago */
    button.btn-pagar {
        background-color: #4CAF50; /* Color de fondo */
        color: #ffffff; /* Color del texto */
        border: none; /* Sin borde */
        border-radius: 5px; /* Esquinas redondeadas */
        padding: 10px 20px; /* Relleno interno */
        font-size: 16px; /* Tamaño del texto */
        cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
        transition: background-color 0.3s ease; /* Animación de transición suave */
    }

    /* Cambia el color de fondo cuando el mouse está sobre el botón */
    button.btn-pagar:hover {
        background-color: #45a049;
    }

/* Estilo de los campos relacionados al costo de la compra */
.costo-compra {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px; /* Reducir el margen inferior */
}

.costo-compra label {
    font-weight: bold;
    color: #333;
    margin-right: 10px; /* Reducir el margen derecho */
}

.costo-compra .valor {
    color: #4CAF50;
}

/* Estilo para el campo Total */
.costo-compra.total .valor {
    font-size: 30px;
    font-weight: bold;
}

</style>
@stop




