<div class="modal fade" id="cantidadMesasModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Cantidad de Mesas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Input para la cantidad de mesas -->
          <div class="mb-3">
            <label for="nuevaCantidadMesasInput" class="form-label">Cantidad de Mesas:</label>
            <input type="number" class="form-control" id="nuevaCantidadMesasInput" placeholder="Ingrese la cantidad">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="guardarNuevaCantidadMesas()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <script>

function guardarNuevaCantidadMesas() {
    var nuevaCantidadMesas = document.getElementById('nuevaCantidadMesasInput').value;
    localStorage.setItem('cantidadMesas', nuevaCantidadMesas);
    // Opcional: Actualizar la visualización de las mesas en la pantalla principal
    // (por ejemplo, recargar la página o actualizar dinámicamente los cuadros de las mesas)
    location.reload(); // Recargar la página
}


  </script>
