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
