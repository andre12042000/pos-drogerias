<div class="modal fade" id="numeroMesaModalPedidos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">

                <div class="container">
                    <div class="row">
                      <div class="col">
                        <h5 class="modal-title" id="solicitantePedido"></h5>
                      </div>
                      <div class="col-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="etiqueta" placeholder="name@example.com">
                            <label for="etiqueta">Etiqueta mesa</label>
                          </div>
                      </div>
                      <div class="col text-end">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">

                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="order-list">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Forma</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Aquí se pintará el contenido del carrito -->
                                    </tbody>
                                </table>
                                <p id="no-products-msg" style="display: none;">No hay productos en el carrito.</p>
                            </div>
                        </div>
                        <div class="col">
                            @livewire('sale.search-product-component')
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="saveOrder()">Guardar Pedido</button>
            </div>
        </div>
    </div>
</div>


<script>
    function saveOrder() {
        // Obtener la mesa desde el contenido de la etiqueta h5
        let mesa = document.getElementById('solicitantePedido').textContent.trim();
        let etiquetaInput = document.getElementById('etiqueta');

        if (etiquetaInput.value.trim() === '') {
            alert('Por favor, ingrese una etiqueta.');
            return; // Salir de la función si el campo de la etiqueta está vacío
        }

        // Verificar si ya hay un pedido para esta mesa en el localStorage
        let pedidoNro = 1; // Valor predeterminado para el primer pedido
        let existingOrders = JSON.parse(localStorage.getItem('orders'));
        if (existingOrders) {
            // Buscar pedidos existentes para esta mesa
            let mesaOrders = existingOrders.filter(order => order.mesa === mesa);
            if (mesaOrders.length > 0) {
                // Si hay pedidos existentes, obtener el número de pedido más alto y aumentarlo en 1
                pedidoNro = Math.max(...mesaOrders.map(order => order.pedidoNro)) + 1;
            }
        }

        // Obtener los detalles del pedido (productos, cantidades, precios, totales) desde el carrito
        let detallesPedido = cart.map(product => {
            return {
                producto_id: product.id,
                forma: product.forma
                cantidad: product.cantidad,
                nombre: product.name,
                precio_unitario: product.precio_venta,
                total: product.total
            };
        });

        // Construir el nuevo pedido
        let nuevoPedido = {
            mesa: mesa,
            pedidoNro: pedidoNro,
            detalles: detallesPedido
        };

        // Obtener los pedidos existentes del localStorage o inicializar un nuevo array si no hay ninguno
        let orders = existingOrders || [];

        // Agregar el nuevo pedido al array de pedidos
        orders.push(nuevoPedido);

        // Guardar el array de pedidos actualizado en el localStorage
        localStorage.setItem('orders', JSON.stringify(orders));

        // Limpiar el carrito después de guardar el pedido
        cart = [];
        updateCartView();

        // Actualizar el estado de las mesas


        // Cerrar el modal
        location.reload();


    }

</script>
