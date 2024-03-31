<div id="cantidadModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ingrese la cantidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="cerrarModalCantidad"></button>
            </div>

            <div class="modal-body p-4">

                <div class="container">

                    <div class="form-group row" style="margin-bottom: 20px;">
                        <label for="selectPresentacion" class="col-form-label col-md-4 mt-1">Presentación</label>
                        <div class="col-md-8">
                            <select class="form-select form-select-lg" id="selectPresentacion"
                                aria-label="Floating label select example">
                                <option value="">Seleccione una opción</option>
                                <option value="disponible_caja">Caja</option>
                                <option value="disponible_blister">Blister</option>
                                <option value="disponible_unidad">Unidad</option>
                            </select>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-4">
                            <label for="cantidadInput" class="mt-1">Cantidad</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group input-group-lg" style="position: relative; width:100%">
                                <input type="number" class="form-control" id="cantidadInput" min="1"
                                    value="1" aria-label="Cantidad">
                                <div class="input-group-append"
                                    style="position: absolute; right: 0; top:0; bottom: 0; display: flex">
                                    <button class="btn btn-outline-secondary" type="button"
                                        style="border-top-left-radius: 0; border-bottom-left-radius:0;"
                                        id="btnIncrementar">+</button>
                                    <button class="btn btn-outline-secondary" type="button"
                                        style="border-top-left-radius: 0; border-bottom-left-radius:0;"
                                        id="btnDecrementar">-</button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group row" style="margin-bottom: 20px;">
                        <label for="precioUnitarioInput" class="col-form-label col-md-4 mt-1">Precio Unitario:</label>
                        <div class="col-md-8">
                            <input type="number" id="precioUnitarioInput" class="form-control text-end" min="1"
                                value="1" aria-label="Precio Unitario" style="height: 50px;">
                        </div>
                    </div>

                    <div class="form-group row" style="margin-bottom: 20px;">
                        <label for="totalPrecioCompraInput" class="col-form-label col-md-4 mt-1">Total:</label>
                        <div class="col-md-8">
                            <input type="number" id="totalPrecioCompraInput" class="form-control text-end" min="1"
                                value="1" aria-label="Total" style="height: 50px;">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="agregarProductosArrayVenta">Agregar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    id="cancelarModalCantidad">Cancelar</button>
            </div>
        </div>
    </div>
</div>

