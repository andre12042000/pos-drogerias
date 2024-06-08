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
                            <th>Stock</th>
                            <th>Precio Caja</th>
                            <th>Precio Unidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr data-product='{{ json_encode($product) }}'>
                                <td>{{ $product->code }} </td>
                                <td>{{ $product->name }}</td>
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

                                <td class="price-cell text-end" data-option="disponible_caja" style="cursor: pointer"
                                    onclick="handlePriceClick(event)">
                                    {{ $product->precio_caja > 0 ? '$' . number_format($product->precio_caja, 0, ',', '.') : '0' }}
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
            </div>
            {{ $products->links() }}
        </div>
    </div>
</div>

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


<script>
    function handlePriceClick(event) {
        var target = event.target;


        if (target.tagName === 'TD' && target.classList.contains('price-cell')) {

            var row = target.parentNode; // Obtener la fila completa
            var productData = JSON.parse(row.getAttribute('data-product'));
            var selectedOption = target.getAttribute('data-option');
            var precio_unitario = 0;

            if (selectedOption === 'disponible_caja') {
                precio_unitario = productData.precio_caja;
            } else if (selectedOption === 'disponible_blister') {
                precio_unitario = productData.precio_blister;
            } else {
                precio_unitario = productData.precio_unidad;
            }

            if (precio_unitario === null || precio_unitario === 0 || precio_unitario === undefined) {
                mostrarError('No es posible vender este producto, con esa presentación');
                return;
            }

            var orders = JSON.parse(localStorage.getItem('orders')) || []; //Obtenemos el localstorage

            // Verificar si el producto ya está en el pedido para MOSTRADOR
            var existingOrder = orders.find(function(order) {
                return order.detalles.some(function(detalle) {
                    return detalle.producto_id === productData.id;
                }) && order.mesa === 'MOSTRADOR';
            });

            if (existingOrder) {
                // Si el producto ya está en el pedido, aumenta la cantidad y actualiza el total
                var existingDetail = existingOrder.detalles.find(function(detalle) {
                    return detalle.producto_id === productData.id;
                });
                existingDetail.cantidad++;
                existingDetail.total = existingDetail.cantidad * existingDetail.precio_unitario;
            } else {
                // Si el producto no está en el pedido, agrega un nuevo pedido
                var newOrder = {
                    mesa: 'MOSTRADOR',
                    pedidoNro: 'Pedido Nro: 1',
                    detalles: [{
                        producto_id: productData.id,
                        forma: selectedOption,
                        nombre: productData.name,
                        cantidad: 1,
                        precio_unitario: precio_unitario, // Supongo que aquí está el precio unitario del producto
                        total: selectedOption // Inicialmente establecido como el total igual al precio unitario
                    }]
                };
                orders.push(newOrder);
            }

            localStorage.setItem('orders', JSON.stringify(orders));

            mostrarProductosMostrador();

        }
    }

    function mostrarError(mensaje) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: mensaje
        });
    }

</script>
