<div>
    @section('title', 'Nueva Cotización')

    <div class="row py-2">
        <div class="col-lg-5">

            <div class="card card-outline card-success" style="height:295px; font-size: 10px;">
                <div class="card-body" style="overflow-y: auto;">
                    <table class="table table-striped">
                        <thead >
                            <tr>
                                <th>Producto</th>
                                <th>Cant</th>
                                <th>Forma</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="productos-en-transaccion">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-outline card-warning">
                <div class="card-body float-right">
                    <div class="row ">
                        <div class="col-7 text-end">

                            <strong >Subtotal</strong>
                        </div>
                        <div class="col-5 ">
                            <h5 id="subtotal"></h5>
                        </div>


                        <div class="col-7 text-end">
                            <strong >% IVA </strong>
                        </div>
                        <div class="col-5 ">
                            <h5 id="iva"></h5>
                        </div>

                        <div class="col-7 text-end">
                            <strong >Descuento</strong>
                        </div>
                        <div class="col-5">
                            <div class="input-group">
                                <input type="text" id="valor_descuento" name="valor_descuento" class="form-control"
                                    aria-describedby="passwordHelpInline" disabled>
                                <span class="input-group-text"><input class="form-check-input mt-1" type="radio"
                                        name="inlineRadioOptions" id="inlineRadio2" value="descuento_efectivo"
                                        onchange="toggleInput(this)">
                                    $</span>
                                <span class="input-group-text"> <input class="form-check-input mt-1" type="radio"
                                        name="inlineRadioOptions" id="inlineRadio1" value="descuento_porcentaje"
                                        onchange="toggleInput(this)">
                                    %</span>
                            </div>

                        </div>

                        <div class="col-4">
                            <a class="btn btn-outline-success" onclick="handleGuardarTransaccion()">Guardar</a>
                        </div>
                        <div class="col-1 ">
                            <a class="btn btn-outline-secondary float-end" title="Restuarar Total"
                                onclick="restablecertotal()"><i class="bi bi-arrow-clockwise"></i></a>
                        </div>
                        <div class="col-2 text-end">
                            <label for="inputPassword6" class="col-form-label">Total</label>

                        </div>
                        <div class="col-5">
                            <h5 class="mt-2" id="total"></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- derecha --}}
        <div class="col-lg-7">
            <div class="card card-outline card-success" style="height:490px; font-size: 10px">
                <div class="row">

                    <div class="col-lg-5 ml-3 mt-3">
                        <div class="form-floating">
                            <select class="form-select" id="cliente" name="cliente"
                                aria-label="Floating label select example">
                                <option value="">Selecciona Un Cliente</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                                @endforeach

                            </select>
                            <label for="floatingSelect">Clientes</label>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div x-data class="mt-3">
                            @include('popper::assets')

                            <div class="input-group  float-right ">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input @popper(Buscador) type="text" class="form-control"
                                    placeholder="Buscar producto" aria-label="Username" aria-describedby="basic-addon1"
                                    wire:model="buscar">
                            </div>


                        </div>
                    </div>

                </div>


                <div class="modal-body" style="overflow-y: auto;">
                    <table class="table table-striped" id="productos-container">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Laboratorio</th>
                                <th>P. Caja</th>
                                <th>P. Blister</th>
                                <th>P. Unidad</th>
                            </tr>
                        </thead>
                        <tbody style="height:365px;">
                            @forelse ($products as $product)
                                <tr data-product='{{ json_encode($product) }}'>
                                    <td>...{{ substr($product->code, -4) }}</td>

                                    <td>

                                        @if (
                                            $product->inventario->cantidad_caja == 0 &&
                                                $product->inventario->cantidad_blister == 0 &&
                                                $product->inventario->cantidad_unidad == 0)
                                            <p data-bs-toggle="tooltip" data-html="true" data-bs-placement="top"
                                                title="No hay productos disponibles." class="text-danger"
                                                style="cursor: default">{{ $product->name }} </p>
                                        @else
                                            <?php
                                            $tooltipContent = 'Cajas: ' . $product->inventario->cantidad_caja . ',   ' . 'Blisters: ' . $product->inventario->cantidad_blister . ',   ' . 'Unidades: ' . $product->inventario->cantidad_unidad;
                                            ?>

                                            <p data-bs-toggle="tooltip" data-html="true" data-bs-placement="top"
                                                title="{{ $tooltipContent }}" style="cursor: default">
                                                {{ $product->name }} </p>
                                        @endif
                                    </td>
                                    <td>
                                        @isset($product->laboratorio->name)
                                            {{ Illuminate\Support\Str::limit($product->laboratorio->name, 12) }}
                                        @else
                                            N/R
                                        @endisset
                                    </td>

                                    <td class="price-cell text-end" data-option="disponible_caja"
                                        style="cursor: pointer" onclick="handlePriceClick(this)">
                                        {{ $product->precio_caja > 0 ? '$' . number_format($product->precio_caja, 0, ',', '.') : '0' }}
                                    </td>
                                    <td class="price-cell text-end" data-option="disponible_blister"
                                        style="cursor: pointer" onclick="handlePriceClick(this)">
                                        {{ $product->precio_blister > 0 ? '$' . number_format($product->precio_blister, 0, ',', '.') : '0' }}
                                    </td>
                                    <td class="price-cell text-end" data-option="disponible_unidad"
                                        style="cursor: pointer" onclick="handlePriceClick(this)">
                                        {{ $product->precio_unidad > 0 ? '$' . number_format($product->precio_unidad, 0, ',', '.') : '0' }}
                                    </td>

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


        </div>

    </div>
</div>
@section('js')





    <script>
        var clienteSeleccionado = '';

        // Función para obtener y almacenar el valor seleccionado del cliente
        function obtenerClienteSeleccionado() {
            // Obtener el elemento select
            var selectCliente = document.getElementById('cliente');

            // Obtener el valor seleccionado del cliente
            clienteSeleccionado = selectCliente.value;

        }



        var selectedProducts = [];
        var totalTransaccion = 0;
        var subtotalTransaccion = 0;
        var ivaTransaccion = 0;
        document.getElementById('total').innerHTML = formatCurrency(totalTransaccion);
        document.getElementById('iva').innerHTML = formatCurrency(ivaTransaccion);
        document.getElementById('subtotal').innerHTML = formatCurrency(subtotalTransaccion);

        function handlePriceClick(cell) {
            var priceText = cell.innerText.trim();
            var priceValue = parseFloat(priceText.replace(/[^\d.-]/g, ''));



            if (!isNaN(priceValue) && priceValue > 0) {
                var selectedOption = cell.dataset.option;
                var productData = JSON.parse(cell.closest('tr').dataset.product);


                if (productData.inventario.cantidad_caja === '0' && productData.inventario.cantidad_blister === '0' &&
                    productData.inventario.cantidad_unidad === '0') {
                    alertSinStock();
                    return;
                }


                var precioUnitario;
                var subtotalIva

                if (selectedOption === 'disponible_caja') {
                    precioUnitario = productData.precio_caja;
                    subtotalIva = productData.valor_iva_caja
                } else if (selectedOption === 'disponible_blister') {
                    precioUnitario = productData.precio_blister;
                    subtotalIva = productData.valor_iva_blister

                } else {
                    precioUnitario = productData.precio_unidad;
                    subtotalIva = productData.valor_iva_unidad

                }

                addProductArray(productData, selectedOption, precioUnitario, subtotalIva)
            }
        }

        function addProductArray(product, forma, precio, subtotalIva) {
            // Busca si el producto ya existe en selectedProducts
            var productoExistente = selectedProducts.find(function(item) {
                return item.product_id === product.id && item.forma === forma;
            });
            if (productoExistente) {
                // Si el producto ya existe, actualiza la cantidad y el subtotal

                productoExistente.cantidad += 1;
                productoExistente.subtotal = productoExistente.cantidad * productoExistente.precio_unitario;
                productoExistente.iva = productoExistente.cantidad * productoExistente.iva;
            } else {
                // Si el producto no existe, agrégalo al array
                var nuevoProducto = {
                    product_id: product.id,
                    product_name: product.name,
                    forma: forma,
                    cantidad: 1,
                    precio_unitario: precio,
                    subtotal: precio,
                    iva: subtotalIva,
                };

                selectedProducts.push(nuevoProducto);

            }

            renderTable();
        }

        function formatCurrency(value) {
            // Utiliza Intl.NumberFormat para formatear como moneda colombiana (COP)
            return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(value);
        }

        function alertSinStock() {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No hay productos en stock, verifica el inventario!",
            });

        }

        function alertSinProductosTransaccion() {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Agrega al menos un producto para continuar la transacción!",
            });

        }

        function alertSinClienteTransaccion() {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Debes seleccionar un cliente para continuar la transacción!",
            });

        }


        function renderTable() {
            // Aquí puedes utilizar el array selectedProducts para actualizar tu tabla
            // Por ejemplo, puedes usar DOM manipulation para añadir filas a una tabla HTML.
            var tabla = document.getElementById('productos-en-transaccion');
            totalTransaccion = 0;
            ivaTransaccion = 0;
            subtotalTransaccion = 0;

            // Limpia la tabla antes de volver a renderizar
            tabla.innerHTML = '';

            // Itera sobre los productos en el array
            selectedProducts.forEach(function(producto, index) {
                // Crea una nueva fila
                var fila = tabla.insertRow();

                // Añade celdas con los datos del producto
                var celdaProduct = fila.insertCell(0);
                var celdaCantidad = fila.insertCell(1);
                var celdaForma = fila.insertCell(2);
                var celdaPrecioUnitario = fila.insertCell(3);
                var celdaSubTotal = fila.insertCell(4);
                var celdaEliminar = fila.insertCell(5);

                celdaProduct.innerHTML = producto.product_name;
                celdaCantidad.innerHTML = producto.cantidad;
                celdaForma.innerHTML = producto.forma;
                celdaPrecioUnitario.innerHTML = formatCurrency(producto.precio_unitario);
                celdaSubTotal.innerHTML = formatCurrency(producto.subtotal);

                var btnEliminar = document.createElement("button");
                btnEliminar.classList.add("btn", "btn-outline-danger"); // Establece el color del botón a gris claro
                btnEliminar.style.border = "none"; // Elimina el borde del botón
                btnEliminar.innerHTML =
                    '<i class="bi bi-trash3" style=" background: transparent;" title="Eliminar"></i>'; // Establece el color del ícono a gris
                btnEliminar.onclick = function() {
                    eliminarProducto(index);
                };

                // Añade el botón a la celda
                celdaEliminar.appendChild(btnEliminar);


                totalTransaccion += producto.subtotal;
                ivaTransaccion += producto.iva;

                subtotalTransaccion = totalTransaccion - ivaTransaccion
            });

            document.getElementById('total').innerHTML = formatCurrency(totalTransaccion);
            document.getElementById('iva').innerHTML = formatCurrency(ivaTransaccion);

            document.getElementById('subtotal').innerHTML = formatCurrency(subtotalTransaccion);

        }

        function restablecertotal() {

            totalTransaccion = ivaTransaccion + subtotalTransaccion; // Restar el valor  al total del sistema
            document.getElementById('total').innerHTML = formatCurrency(totalTransaccion);

        }

        function toggleInput(radio) {
            var valor_descuento_input = document.getElementById("valor_descuento");
            if (radio.value === "descuento_porcentaje") {
                valor_descuento_input.maxLength = 2; // Establecer máximo de 2 caracteres
                valor_descuento_input.value = ''; // Limpiar el valor del input
                valor_descuento_input.disabled = false; // Habilitar el input
                totalTransaccion = ivaTransaccion + subtotalTransaccion; // Restar el valor  al total del sistema

            } else {
                valor_descuento_input.maxLength = 7; // Establecer máximo de 7 caracteres
                valor_descuento_input.value = ''; // Limpiar el valor del input
                valor_descuento_input.disabled = false; // Habilitar el input
                totalTransaccion = ivaTransaccion + subtotalTransaccion; // Restar el valor  al total del sistema

            }
            document.getElementById('total').innerHTML = formatCurrency(totalTransaccion);

        }

        // Esta función calcula el total cuando se hace un cambio en el campo de descuento
        function calculateTotal() {
            var valor_descuento_input = document.getElementById("valor_descuento");
            var valor_descuento = parseFloat(valor_descuento_input.value);
            var radios = document.getElementsByName("inlineRadioOptions");
            var selectedRadioValue = "";

            for (var i = 0; i < radios.length; i++) {
                if (radios[i].checked) {
                    selectedRadioValue = radios[i].value;
                    break;
                }
            }

            if (selectedRadioValue === "descuento_porcentaje" && valor_descuento > 0 && totalTransaccion > 0) {
                // Calcular el descuento en base al porcentaje
                var descuento = totalTransaccion * (valor_descuento / 100);
                totalTransaccion -= descuento; // Restar el descuento al total del sistema
            } else {
                if (valor_descuento >= 50 && totalTransaccion > 0) {
                    // Sumar el descuento en efectivo al total del sistema
                    totalTransaccion -= valor_descuento; // Restar el descuento al total del sistema
                }

            }
            document.getElementById('total').innerHTML = formatCurrency(totalTransaccion);
        }

        // Esta función asocia la ejecución de calculateTotal al evento change del campo de descuento
        document.getElementById("valor_descuento").addEventListener("change", calculateTotal);


        function eliminarProducto(index) {
            // Elimina el producto del array en el índice dado
            selectedProducts.splice(index, 1);

            // Vuelve a renderizar la tabla
            renderTable();
        }

        function handleGuardarTransaccion() {
            descuentoTototal = 0;
            descuentoTototal = subtotalTransaccion + ivaTransaccion - totalTransaccion
            obtenerClienteSeleccionado()
            if (clienteSeleccionado == '') {
                alertSinClienteTransaccion();
                return;
            }
            if (!selectedProducts || selectedProducts.length === 0 || totalTransaccion === 0) {
                alertSinProductosTransaccion();
                return;
            }

            Livewire.emit('guardarCotizacionEvent', {
                selectedProducts: selectedProducts,
                subtotalTransaccion: subtotalTransaccion,
                ivaTransaccion: ivaTransaccion,
                descuentoTototal: descuentoTototal,
                clienteSeleccionado: clienteSeleccionado,
                totalTransaccion: totalTransaccion
            });

        }
    </script>


    <script>
        window.addEventListener('transaccion-generada', event => {
            const numeroVenta = event.detail.transaccion;
            Swal.fire({
                icon: "success",
                title: "Transacción realizada correctamente",
                text: `Número de transacción: ${numeroVenta}`,
                showConfirmButton: false,
                timer: 3000
            });
            setTimeout(() => {
                const rutaDeseadaUrl = '{{ route('cotizaciones.cotizaciones.list') }}';

                // Redirigir a la ruta deseada
                window.location.href = rutaDeseadaUrl;
            }, 1500);

        })
    </script>


@stop

@section('css')

    <style>
        /* Estilo para la tabla */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        /* Estilo para las celdas del encabezado */
        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        /* Estilo para las filas impares */
        .table tbody tr:nth-child(odd) {
            background-color: #f8f9fa;
        }

        /* Estilo para el mensaje cuando no hay registros */
        .table tbody tr td[colspan="6"] {
            text-align: center;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        /* Alinea las celdas de precio a la derecha */
        .precio {
            text-align: right;
        }
    </style>

    <style>
        /* Estilo para la tabla */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        /* Estilo para las celdas del encabezado */
        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        /* Estilo para las filas impares */
        .table tbody tr:nth-child(odd) {
            background-color: #f8f9fa;
        }

        /* Estilo para el mensaje cuando no hay registros */
        .table tbody tr td[colspan="6"] {
            text-align: center;
            padding: 10px;
        }
    </style>

@stop
