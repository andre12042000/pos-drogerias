<div id="cantidadModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ingrese la cantidad</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <div class="row">
                <label for="selectPresentacion">Presentación</label>
                    <select class="form-select" id="selectPresentacion" aria-label="Floating label select example">
                      <option value="">Seleccione una opción</option>
                      <option value="disponible_caja">Caja</option>
                      <option value="disponible_blister">Blister</option>
                      <option value="disponible_unidad">Unidad</option>
                    </select>


            </div>
            <div class="row">
                <label for="cantidadInput">Cantidad:</label>
                <input type="number" id="cantidadInput" class="form-control" min="1" value="1">
            </div>

            <div class="row">
                <label for="precioUnitarioInput">Precio Unitario:</label>
                <input type="number" id="precioUnitarioInput" class="form-control" min="1" value="1">
            </div>

            <div class="row">
                <label for="totalPrecioCompraInput">Total:</label>
                <input type="number" id="totalPrecioCompraInput" class="form-control" min="1" value="1">
            </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="agregarProductosArrayVenta">Agregar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
