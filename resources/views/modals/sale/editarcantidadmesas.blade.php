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
                    <input type="number" class="form-control" id="nuevaCantidadMesasInput"
                        placeholder="Ingrese la cantidad">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" onclick="guardarNuevaCantidadMesas()">Guardar</button>
                 <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>

            </div>
        </div>
    </div>
</div>

<script>
    // Obtener el valor actual del local storage para la cantidad de mesas
    var cantidadMesas = localStorage.getItem('cantidadMesas');

    // Verificar si la cantidad de mesas ya está almacenada en el localStorage
    if (cantidadMesas !== null) {
        // Si la cantidad de mesas está almacenada, establecerla como el valor del input
        document.getElementById('nuevaCantidadMesasInput').value = cantidadMesas;
    } else {
        // Si no hay cantidad de mesas almacenada, dejar el input vacío o con un valor predeterminado
        document.getElementById('nuevaCantidadMesasInput').value = ''; // o cualquier otro valor predeterminado
    }



    function guardarNuevaCantidadMesas() {
        var nuevaCantidadMesas = document.getElementById('nuevaCantidadMesasInput').value;
        localStorage.setItem('cantidadMesas', nuevaCantidadMesas);
        // Opcional: Actualizar la visualización de las mesas en la pantalla principal
        // (por ejemplo, recargar la página o actualizar dinámicamente los cuadros de las mesas)
        location.reload(); // Recargar la página
    }
</script>
