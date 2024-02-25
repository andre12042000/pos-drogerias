<div id="ajustarInventarioModal" class="modal">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #17A2B8">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Ajustar Inventario</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="cerrarModalajsutes()"></button>
        </div>
        <div id="contenidoAjustarModal">
 <div class="modal-body">

    <div class="input-group has-validation">
        <span class="input-group-text"><i class="bi bi-box"></i></span>
        <div class="form-floating is-invalid">
          <input type="text" class="form-control is-invalid" id="cant_caja" placeholder="Username" required>
          <label for="cant_caja">Cantidad Cajas</label>
        </div>
        <div class="invalid-feedback">
           hola
        </div>
      </div>


      <div class="input-group has-validation">
        <span class="input-group-text"><i class="bi bi-grid-3x2"></i></span>
        <div class="form-floating is-invalid">
          <input type="text" class="form-control is-invalid" id="cant_blister" placeholder="Username" required>
          <label for="cant_blister">Cantidad Blister</label>
        </div>
        <div class="invalid-feedback">
          hola
        </div>
      </div>


      <div class="input-group has-validation">
        <span class="input-group-text"><i class="bi bi-square"></i></span>
        <div class="form-floating is-invalid">
          <input type="text" class="form-control is-invalid" id="cant_unidad" placeholder="Username" required>
          <label for="cant_unidad">Cantidad Unidad</label>
        </div>
        <div class="invalid-feedback">
           hola

        </div>
      </div>


        </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="cerrarModalajsutes()">Cerrar</button>
          <button type="button" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </div>
</div>
