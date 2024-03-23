<div class="modal fade" id="numeroMesaModalPedidos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="solicitantePedido"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    function updateCartView() {
        // Obtener la tabla y el cuerpo de la tabla
        let tableBody = document.querySelector('.order-list tbody');
        let noProductsMsg = document.getElementById('no-products-msg');

        // Limpiar la tabla antes de actualizarla
        tableBody.innerHTML = '';

        if (cart.length === 0) {
            // Mostrar el mensaje de "No hay productos en el carrito"
            noProductsMsg.style.display = 'block';
        } else {
            // Ocultar el mensaje de "No hay productos en el carrito"
            noProductsMsg.style.display = 'none';

            // Recorrer los productos en el carrito y agregarlos a la tabla
            cart.forEach(product => {
                let row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.name}</td>
                    <td class="text-center">${product.cantidad}</td>
                    <td class="text-end">$${product.precio_venta}</td>
                    <td class="text-end">$${product.total}</td>
                    <td>

                            <i class="bi bi-trash" onclick="removeFromCart(${product.id})" style="cursor:pointer;"></i> <!-- Icono de papelera -->

                    </td>
                `;
                tableBody.appendChild(row);
            });
        }
    }

    function removeFromCart(productId) {
        // Filtrar el producto a eliminar del carrito
        cart = cart.filter(product => product.id !== productId);
        // Actualizar la vista del carrito
        updateCartView();
    }

    function saveOrder() {
        // Obtener la mesa desde el contenido de la etiqueta h5
    let mesa = document.getElementById('solicitantePedido').textContent.trim();

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
