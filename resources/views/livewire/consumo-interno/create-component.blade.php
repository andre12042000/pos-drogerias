<div>
    <div class="row py-5">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Forma</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="productos-en-transaccion" style="height:400px;">

                        </tbody>
                    </table>
                </div>


                <div class="card-footer float-right">
                    <div class="col-12 text-end">
                        <h4 id="total"></h4>
                    </div>
                </div>
            </div>
        </div>
        {{-- derecha --}}
        <div class="col-lg-6">
            <div class="card">
                <div x-data class="mt-2">
                    @include('popper::assets')

                    <div class="input-group  float-right col-5">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input @popper(Buscador) type="text" class="form-control" placeholder="Buscar producto"
                            aria-label="Username" aria-describedby="basic-addon1" wire:model="buscar">
                    </div>
                </div>
                <div class="modal-body">
                    <table class="table table-striped" id="productos-container">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Laboratorio</th>
                                <th>C. Caja</th>
                                <th>C. Blister</th>
                                <th>C. Unidad</th>
                            </tr>
                        </thead>
                        <tbody style="height:365px;">
                            @forelse ($products as $product)
                                <tr data-product='{{ json_encode($product) }}'>
                                    <td>{{ $product->code }} </td>
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
                                        {{ $product->precio_caja > 0 ? '$' . number_format($product->costo_caja, 0, ',', '.') : '0' }}
                                    </td>
                                    <td class="price-cell text-end" data-option="disponible_blister"
                                        style="cursor: pointer" onclick="handlePriceClick(this)">
                                        {{ $product->precio_blister > 0 ? '$' . number_format($product->costo_blister, 0, ',', '.') : '0' }}
                                    </td>
                                    <td class="price-cell text-end" data-option="disponible_unidad"
                                        style="cursor: pointer" onclick="handlePriceClick(this)">
                                        {{ $product->precio_unidad > 0 ? '$' . number_format($product->costo_unidad, 0, ',', '.') : '0' }}
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

    <div class="row">

        <div class="d-grid gap-2">
            <button class="btn btn-outline-success" type="button" style="height: 50px;" onclick="handleGuardarTransaccion()" >Guardar</button>
        </div>

    </div>


</div>



@section('js')
    <script>
        var selectedProducts = [];
        var totalTransaccion = 0;
        document.getElementById('total').innerHTML = 'Total: ' + formatCurrency(totalTransaccion);

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

                if (selectedOption === 'disponible_caja') {
                    precioUnitario = productData.costo_caja;
                } else if (selectedOption === 'disponible_blister') {
                    precioUnitario = productData.costo_blister;
                } else {
                    precioUnitario = productData.costo_unidad;
                }

                addProductArray(productData, selectedOption, precioUnitario)
            }
        }

        function addProductArray(product, forma, precio) {
            // Busca si el producto ya existe en selectedProducts
            var productoExistente = selectedProducts.find(function(item) {
                return item.product_id === product.id && item.forma === forma;
            });

            if (productoExistente) {
                // Si el producto ya existe, actualiza la cantidad y el subtotal
                productoExistente.cantidad += 1;
                productoExistente.subtotal = productoExistente.cantidad * productoExistente.precio_unitario;
            } else {
                // Si el producto no existe, agrégalo al array
                var nuevoProducto = {
                    product_id: product.id,
                    product_name: product.name,
                    forma: forma,
                    cantidad: 1,
                    precio_unitario: precio,
                    subtotal: precio,
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


        function renderTable() {
            // Aquí puedes utilizar el array selectedProducts para actualizar tu tabla
            // Por ejemplo, puedes usar DOM manipulation para añadir filas a una tabla HTML.
            var tabla = document.getElementById('productos-en-transaccion');
            totalTransaccion = 0;

            // Limpia la tabla antes de volver a renderizar
            tabla.innerHTML = '';

            // Itera sobre los productos en el array
            selectedProducts.forEach(function(producto) {
                // Crea una nueva fila
                var fila = tabla.insertRow();

                // Añade celdas con los datos del producto
                var celdaProduct = fila.insertCell(0);
                var celdaCantidad = fila.insertCell(1);
                var celdaForma = fila.insertCell(2);
                var celdaPrecioUnitario = fila.insertCell(3);
                var celdaSubTotal = fila.insertCell(4);

                celdaProduct.innerHTML = producto.product_name;
                celdaCantidad.innerHTML = producto.cantidad;
                celdaForma.innerHTML = producto.forma;
                celdaPrecioUnitario.innerHTML = formatCurrency(producto.precio_unitario);
                celdaSubTotal.innerHTML = formatCurrency(producto.subtotal);


                totalTransaccion += producto.subtotal;


            });

            document.getElementById('total').innerHTML = 'Total: ' + formatCurrency(totalTransaccion);
        }

        function handleGuardarTransaccion()
        {
            if (!selectedProducts || selectedProducts.length === 0 || totalTransaccion === 0) {
                alertSinProductosTransaccion();
                return;
            }

            Livewire.emit('guardarConsumoInternoEvent', {
                selectedProducts: selectedProducts,
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
            const rutaDeseadaUrl = '{{ route("consumo_interno.index") }}';

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

@stop
