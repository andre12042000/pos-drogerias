<div id="addProductCode" class="modal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Añadir producto</h5>
          <button type="button" class="btn-close" id="cerrarModalBtnCreate" aria-label="Close"></button>
        </div>

        <div class="modal-body p-4">

            <div class="container">

                <input type="hidden" id="ivaInputCreate" class="form-control" min="1" value="0" aria-label="Precio Unitario" style="height: 50px;">

                <div class="form-group row" style="margin-bottom: 20px;">
                  <label for="selectPresentacionCreate" class="col-form-label col-md-4 mt-1">Presentación</label>
                  <div class="col-md-8">
                    <select class="form-select form-select-lg" id="selectPresentacionCreate" aria-label="Floating label select example">
                      <option value="">Seleccione una opción</option>
                      <option value="disponible_caja">Caja</option>
                      <option value="disponible_blister">Blister</option>
                      <option value="disponible_unidad">Unidad</option>
                    </select>
                  </div>
                </div>

                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-4">
                        <label for="cantidadInputCreate" class="mt-1">Cantidad</label>
                      </div>
                      <div class="col-md-8">
                        <div class="input-group input-group-lg" style="position: relative; width:100%">
                            <input type="number" class="form-control" id="cantidadInputCreate" min="1" value="1" aria-label="Cantidad">
                            <div class="input-group-append" style="position: absolute; right: 0; top:0; bottom: 0; display: flex">
                              <button class="btn btn-outline-secondary" type="button" style="border-top-left-radius: 0; border-bottom-left-radius:0;" id="btnIncrementarCreate" style="width: 50px;">+</button>
                              <button class="btn btn-outline-secondary" type="button" style="border-top-left-radius: 0; border-bottom-left-radius:0;" id="btnDecrementarCreate" style="width: 50px;">-</button>
                            </div>
                          </div>
                      </div>

                </div>

                <div class="form-group row" style="margin-bottom: 20px;">
                  <label for="precioUnitarioInputCreate" class="col-form-label col-md-4 mt-1">Precio Unitario:</label>
                  <div class="col-md-8">
                    <input type="number" id="precioUnitarioInputCreate" class="form-control" min="1" value="1" aria-label="Precio Unitario" style="height: 50px;">
                  </div>
                </div>

                <div class="form-group row" style="margin-bottom: 20px;">
                    <label for="precioUnitarioCreate" class="col-form-label col-md-4 mt-1">Descuento:</label>
                    <div class="col-md-8">
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" aria-label="Text input with dropdown button" id="inputDescuentoCreate" disabled style="height: 50px;">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="descuentoBtnCreate" style="height: 50px; width: 50px;"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                              <li>
                                <div class="form-check form-check-inline m-2">
                                    <input class="form-check-input" type="radio" name="tipoDescuento" id="descuentoPorcentajeCreate" value="porcentaje">
                                    <label class="form-check-label" for="descuentoPorcentaje">Porcentaje (%)</label>
                                </div>
                              </li>
                              <li>
                                <div class="form-check form-check-inline m-2">
                                    <input class="form-check-input" type="radio" name="tipoDescuento" id="descuentoValorFijoCreate" value="valor_fijo">
                                    <label class="form-check-label" for="descuentoValorFijo">Valor fijo ($)</label>
                                </div>
                              </li>

                            </ul>
                          </div>

                    </div>
                </div>



                <div class="form-group row" style="margin-bottom: 20px;">
                  <label for="totalPrecioCompraInputCreate" class="col-form-label col-md-4 mt-1">Total:</label>
                  <div class="col-md-8">
                    <input type="number" id="totalPrecioCompraInputCreate" class="form-control" min="1" value="1" aria-label="Total" style="height: 50px;" disabled readonly>
                  </div>
                </div>
              </div>

        </div>
        <div class="modal-footer">

          <button type="button" class="btn btn-danger" id="cancelarTransaccionCreate" onclick="cerrarModalYLimpiarCreate()">Cancelar</button>
          <button type="button" class="btn btn-outline-success" id="guardarItemVenta" style="width: 300px;">Añadir producto</button>
        </div>
      </div>
    </div>
  </div>
