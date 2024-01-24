<div>
    @include('popper::assets')
    <div id="alerts"> @include('includes.alert')</div>
    <div class="card">
        <div class="card-header">

            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                @if ($empresa->modal_aut_produc == 1)
                                @endif
                                <i class="bi bi-search mr-3 mt-2" style="cursor: pointer" data-toggle="modal"
                                    data-target="#searchproduct"></i>
                            </div>
                            <input type="text"
                                class="form-control @if ($error_search) is-invalid @endif"
                                id="inlineFormInputGroup" placeholder="Buscar producto por código"
                                wire:model.lazy ="codigo_de_producto" wire:keydown.enter="searchProductCode" autofocus
                                autocomplete="disabled"> <!--Buscador por código -->
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
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1"
                                value="1" disabled>
                            <label class="form-check-label" for="inlineRadio1">Si</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2"
                                value="0" disabled checked>
                            <label class="form-check-label" for="inlineRadio2">No</label>
                        </div>

                    </div>
                    <div class="col">

                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-3 col-form-label">Transacción</label>
                            <div class="col-sm-9">
                                <select class="form-select" aria-label="Default select example" wire:model.lazy = 'tipo_operacion' disabled>
                                    <option selected></option>
                                    <option value="VENTA">Venta</option>
                                    <option value="VENTA_CREDITO">Venta credito</option>
                                    <option value="CONSUMO_INTERNO">Consumo interno</option>
                                    <option value="COTIZACION">Cotización</option>
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
                                        style="cursor: pointer"></i></button>
                                <button class="btn btn-outline-secondary" type="button"data-toggle="modal"
                                    data-target="#clientmodal"><i class="bi bi-plus-circle-fill"
                                        style="cursor: pointer"></i></button>
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
                    <div class="col">
                        <label for="exampleFormControlInput1" class="form-label">Imprimir recibo</label>
                        <br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="opcionRadio" id="opcionSi" value="1">
                            <label class="form-check-label" for="opcionSi">Si</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="opcionRadio" id="opcionNo" value="0" checked>
                            <label class="form-check-label" for="opcionNo">No</label>
                        </div>

                    </div>
                    <div class="col">

                        <div class="form-floating">
                            <select class="form-select" id="selectMetodoPago" name="metodoPagoSelect" aria-label="Floating label select example">
                                @foreach ($metodos_pago as $metodo)
                                    <option value="{{ $metodo->id }}">{{ $metodo->name }}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelect">Método de pago</label>
                          </div>

                          <div class="form-floating mt-2">
                            <input type="number" class="form-control" id="inputCantidadPagada" name="inputCantidadPagada" placeholder="name@example.com">
                            <label for="floatingInput">Cantidad Pagada</label>
                          </div>
                          <div class="form-floating mt-2">
                            <input type="number" class="form-control" id="inputCambio" name="inputCambio" placeholder="Password" disabled>
                            <label for="floatingPassword">Cambio</label>
                          </div>
                    </div>
                    <div class="col columna-cuentas">
                        <div class="costo-compra mt-2">
                            <label>Descuento:</label>
                            <span class="descuentoGlobal"></span>
                        </div>
                        <div class="costo-compra mt-2">
                            <label>Subtotal:</label>
                            <span class="subTotalGlobal"></span>
                        </div>
                        <div class="costo-compra mt-2">
                            <label>IVA:</label>
                            <span class="ivaTotalGlobal"></span>
                        </div>
                        <div class="costo-compra total mt-1">
                            <label>Total:</label>
                            <span class="granTotal"></span>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 mt-4">
                        <div class="d-grid">
                            <button class="btn btn-primary btn-pagar" id="pagarBtn" type="button"><strong>Pagar</strong></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @include('modals.sale.forma_cantidad')

        <script src="{{ asset('js/ventas/procesoVenta.js') }}"></script>
        <script>
            // Verificar la condición
            @if ($empresa->modal_aut_produc == '1')
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

.btn-eliminar {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        font-size: 16px;
        color: #888; /* Color normal del icono */
        transition: color 0.3s; /* Transición de color para el efecto hover */
        display: inline-block; /* Hacer que el botón sea un bloque en línea para poder alinear a la derecha */

        /* Estilo de cursor al pasar el ratón sobre el botón */
        cursor: pointer;
    }

    .btn-eliminar:hover {
        color: #dc3545; /* Color del icono al pasar el ratón sobre el botón */
    }

    /* Alineación a la derecha y colocación al final de la celda */
    .celda-eliminar {
        text-align: right !important; /* Alinear el contenido a la derecha */
        white-space: nowrap; /* Evitar que el contenido se divida en varias líneas */
    }
        /* Estilo para el botón de pago si es relevante en tu formulario */
        .campo-pago button {
            padding: 8px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }


        /* Estilo para el nombre del método de pago */
        .metodo-pago span {
            font-size: 14px;
        }

        .ocultar-columna-id {
            display: none;
        }

        .table-responsive-md {
            max-height: 400px;
            /* Establece la altura máxima que prefieras */
            overflow-y: auto;
            /* Añade un scrollbar vertical cuando sea necesario */
        }

        /* Estilo del botón de pago */
        button.btn-pagar {
            background-color: #4CAF50;
            /* Color de fondo */
            color: #ffffff;
            /* Color del texto */
            border: none;
            /* Sin borde */
            border-radius: 5px;
            /* Esquinas redondeadas */
            padding: 10px 20px;
            /* Relleno interno */
            font-size: 16px;
            /* Tamaño del texto */
            cursor: pointer;
            /* Cambia el cursor al pasar sobre el botón */
            transition: background-color 0.3s ease;
            /* Animación de transición suave */
        }

        /* Cambia el color de fondo cuando el mouse está sobre el botón */
        button.btn-pagar:hover {
            background-color: #45a049;
        }

        /* Agrega estilos generales a los elementos de clase costo-compra */
        .costo-compra {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Estilo específico para la etiqueta (label) */
        .costo-compra label {
            font-weight: bold;
            margin-right: 10px;
        }

        /* Estilo específico para el span */
        .costo-compra span {
            font-size: 16px;
            /* Ajusta el tamaño de fuente según tus preferencias */
        }

        /* Estilo específico para el elemento total */
        .costo-compra.total label,
        .costo-compra.total span {
            font-size: 30px;
            font-weight: bold;
        }

        /* Alineación del texto en el elemento total */
        .costo-compra.total {
            text-align: right;
        }
    </style>
@stop
