<div x-data>
    @include('popper::assets')
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <div class="input-group  float-right col-5">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                    <input @popper(Buscador) type="text" class="form-control" placeholder="Buscar producto"
                        aria-label="Username" aria-describedby="basic-addon1" wire:model="buscar">
                </div>
                <button type="button" class="btn-close " data-dismiss="modal" id="closeModalButton" wire:click="cancel"
                    aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <table class="table table-striped" id="productos-container">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Ubicación</th>
                            <th>Stock</th>
                            <th>Laboratorio</th>
                            <th>P. Caja</th>
                            <th>P. Blister</th>
                            <th>P. Unidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr data-product='{{ json_encode($product) }}'>
                                <td>{{ $product->code }} </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->ubicacion->name }}</td>
                                <td class="text-center">
                                    @if (
                                        $product->inventario->cantidad_caja == 0 &&
                                            $product->inventario->cantidad_blister == 0 &&
                                            $product->inventario->cantidad_unidad == 0)
                                        <i class="bi bi-stop-circle text-danger" data-bs-toggle="tooltip"
                                            data-html="true" data-bs-placement="top"
                                            title="No hay productos disponibles." style="cursor: not-allowed;"></i>
                                    @else
                                        <?php
                                        $tooltipContent = 'Cajas: ' . $product->inventario->cantidad_caja . ',   ' . 'Blisters: ' . $product->inventario->cantidad_blister . ',   ' . 'Unidades: ' . $product->inventario->cantidad_unidad;
                                        ?>
                                        <i class="bi bi-check-circle text-success" data-bs-toggle="tooltip"
                                            data-html="true" data-bs-placement="top" title="{{ $tooltipContent }}"
                                            style="cursor: not-allowed;"></i>
                                    @endif
                                </td>

                                <td>
                                    @isset($product->laboratorio->name)
                                        {{ Illuminate\Support\Str::limit($product->laboratorio->name, 12) }}
                                    @else
                                        N/R
                                    @endisset
                                </td>


                                <td class="price-cell text-end" data-option="disponible_caja" style="cursor: pointer"
                                    onclick="handlePriceClick(event)">
                                    {{ $product->precio_caja > 0 ? '$' . number_format($product->precio_caja, 0, ',', '.') : '0' }}
                                </td>
                                <td class="price-cell text-end" data-option="disponible_blister" style="cursor: pointer"
                                    onclick="handlePriceClick(event)">
                                    {{ $product->precio_blister > 0 ? '$' . number_format($product->precio_blister, 0, ',', '.') : '0' }}
                                </td>
                                <td class="price-cell text-end" data-option="disponible_unidad" style="cursor: pointer"
                                    onclick="handlePriceClick(event)">
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


                <div class="modal-footer mt-2">
                    <nav class="" aria-label="">
                        <ul class="pagination">
                            {{ $products->links() }}
                        </ul>
                    </nav>
                </div>


            </div>
        </div>
    </div>
</div>



<script>
    function handlePriceClick(event) {
        var target = event.target;


        if (target.tagName === 'TD' && target.classList.contains('price-cell')) {


            let row = target.parentNode; // Obtener la fila completa
            let productData = JSON.parse(row.getAttribute('data-product'));
            let selectedOption = target.getAttribute('data-option');
            let precio_unitario = 0;
            let iva = 0;

            if(parseInt(productData.inventario.cantidad_caja) === 0 && parseInt(productData.inventario.cantidad_blister) === 0 && parseInt(productData.inventario.cantidad_unidad) === 0){
                mostrarError('No hay productos en stock, por favor verifica el inventario y vuelve a intentarlo');
                return;
            }

            if (selectedOption === 'disponible_caja') {
                precio_unitario = productData.precio_caja;
                iva = productData.valor_iva_caja;
            } else if (selectedOption === 'disponible_blister') {
                precio_unitario = productData.precio_blister;
                iva = productData.valor_iva_blister;
            } else {
                precio_unitario = productData.precio_unidad;
                iva = productData.valor_iva_unidad;
            }

            var orders = JSON.parse(localStorage.getItem('ordersPos')) || []; //Obtenemos el localstorage

            // Verificar si el producto ya está en el pedido para MOSTRADOR
            var existingOrder = orders.find(function(order) {
                return order.producto_id === productData.id && order.forma === selectedOption;
            });

            if (existingOrder) {
                // Si el producto ya está en el pedido con la misma forma, aumenta la cantidad y actualiza el total
                existingOrder.cantidad++;
                existingOrder.total = existingOrder.cantidad * existingOrder.precio_unitario;
                existingOrder.iva = existingOrder.cantidad * iva;
            } else {
                // Si el producto no está en el pedido o tiene una forma diferente, agrega un nuevo pedido
                var newOrder = {
                    key: Date.now(), // Se añade una clave única basada en la fecha actual
                    producto_id: productData.id,
                    forma: selectedOption,
                    code: productData.code,
                    nombre: productData.name,
                    cantidad: 1,
                    precio_unitario: precio_unitario, // Supongo que aquí está el precio unitario del producto
                    iva: iva,
                    descuento: 0,
                    total: precio_unitario // Inicialmente establecido como el total igual al precio unitario
                };
                orders.push(newOrder);
            }
console.log(newOrder);
            localStorage.setItem('ordersPos', JSON.stringify(orders));

            mostrarDatosLocalStorageEnTabla();
        }
    }
</script>


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
    </style>

@stop
