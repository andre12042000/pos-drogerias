<div id="ajustarInventarioModal" class="modal">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #17A2B8">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Ajustar Inventario</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="cerrarModalajsutes()"></button>
            </div>
            <div id="contenidoAjustarModal">
                <div class="modal-body">

                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text"><i class="bi bi-box"></i></span>
                        <div class="form-floating ">
                            <input type="number" class="form-control " id="stock_cajas" placeholder="Username" required autofocus min="0" autocomplete="nope"
                            >
                            <label for="cant_caja">Cantidad Cajas</label>
                        </div>
                        <div class="invalid-feedback" id="error_cajas" style="display: none"></div>
                    </div>

                    <input type="hidden" id="product_id">
                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text"><i class="bi bi-grid-3x2"></i></span>
                        <div class="form-floating ">
                            <input type="number" class="form-control " id="stock_blisters" placeholder="Username"
                                required min="0" autocomplete="nope">
                            <label for="cant_blister">Cantidad Blister</label>
                        </div>
                        <div class="invalid-feedback" id="error_blister" style="display: none"></div>
                    </div>


                    <div class="input-group has-validation mb-3">
                        <span class="input-group-text"><i class="bi bi-square"></i></span>
                        <div class="form-floating ">
                            <input type="number" class="form-control " id="stock_unidades" placeholder="Username"
                                required min="0" autocomplete="nope">
                            <label for="cant_unidad">Cantidad Unidad</label>
                        </div>
                        <div class="invalid-feedback" id="error_unidad" style="display: none"></div>
                    </div>


                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    onclick="cerrarModalajsutes()">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="ajustarInventario()" style="width: 200px;" id="BtnAjustarInventario">
                        Guardar
                        <img class="loader" src="{{ asset('img/loading.gif') }}" alt="Cargando..." width="30px;" style="vertical-align: middle; display: none;" />
                    </button>
            </div>
        </div>
    </div>
</div>
