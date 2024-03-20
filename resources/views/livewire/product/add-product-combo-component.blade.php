<div class="modal-content">
    <div class="modal-header bg-success">

        <div class="input-group  float-right col-5">
            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
            <input @popper(Buscador) type="text" class="form-control" placeholder="Buscar producto" aria-label="Username"
                aria-describedby="basic-addon1" wire:model="buscar">
        </div>
        <button type="button" class="btn-close " data-dismiss="modal" id="closeModalButton"
            aria-label="Close"></button>


    </div>
    <div class="modal-body">
        <div class="row m-3">

            <table class="table table-striped" id="productos-container">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Costo. Caja</th>
                        <th>Costo. Blister</th>
                        <th>Costo. Unidad</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr data-product='{{ json_encode($product) }}'>
                            <td>{{ $product->code }} </td>
                            <td>{{ $product->name }}</td>
                            <td class="price-cell text-end" data-option="disponible_caja" style="cursor: pointer"
                                onclick="handlePriceClickCombo(this)">
                                {{ $product->costo_caja > 0 ? '$' . number_format($product->costo_caja, 0, ',', '.') : '0' }}
                            </td>
                            <td class="price-cell text-end" data-option="disponible_blister" style="cursor: pointer"
                                onclick="handlePriceClickCombo(this)">
                                {{ $product->costo_blister > 0 ? '$' . number_format($product->costo_blister, 0, ',', '.') : '0' }}
                            </td>
                            <td class="price-cell text-end" data-option="disponible_unidad" style="cursor: pointer"
                                onclick="handlePriceClickCombo(this)">
                                {{ $product->costo_unidad > 0 ? '$' . number_format($product->costo_unidad, 0, ',', '.') : '0' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <p>No se encontraron registros...</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="row m-2">
            <h4 class="card-title mb-0 mt-2 mb-2"><strong>Detalles del producto</strong></h4>
            <br>
            <div class="col-sm-2">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="codigo" placeholder="name@example.com"
                       disabled>
                    <label for="codigo">Código</label>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="descripcion" placeholder="name@example.com"
                        disabled>
                    <label for="descripcion">Nombre / Descripción</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-floating">
                    <select class="form-select" id="forma"  aria-label="Floating label select example" disabled>
                      <option selected>Seleccione una opción</option>
                      <option value="disponible_caja">Caja</option>
                      <option value="disponible_blister">Blister</option>
                      <option value="disponible_unidad">Unidad</option>
                    </select>
                    <label for="floatingSelect">Forma</label>
                  </div>
            </div>
            <div class="col-sm-2">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control text-end" id="costo_unitario" placeholder="name@example.com"
                        disabled>
                    <label for="costo_unitario">Costo Unit.</label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control text-end" id="cantidad" placeholder="name@example.com" min="1" autocomplete="off">
                    <label for="cantidad">Cantidad</label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer m-3">
        <x-adminlte-button class="float-right" wire:click="cancel" theme="danger" label="Cancelar"
            data-dismiss="modal" />

        <button type="button"  id="addProductBtn"
            class="btn btn-outline-success float-right ml-2 mb-1" disabled>Añadir Producto Combo

            <img class="loader" src="{{ asset('img/loading.gif') }}" alt="Cargando..." width="30px;"
                style="display: none;" />

        </button>

    </div>
</div>

@section('js')
<script>
    function handlePriceClickCombo(element) {

        // Obtener el producto seleccionado desde el atributo data-product
        var product = JSON.parse(element.parentNode.getAttribute('data-product'));
        var option = element.getAttribute('data-option');


        // Obtener el tipo de opción de precio desde el atributo data-option
        // Actualizar los detalles del producto con los valores correspondientes
        document.getElementById('codigo').value = product.code;
        document.getElementById('descripcion').value = product.name;
        document.getElementById('forma').value = option; // tenemo la opción seleccinada, caja, blsiter o unidad
        document.getElementById('costo_unitario').value = formatCurrency(option === 'disponible_caja' ? product.costo_caja : option === 'disponible_blister' ? product.costo_blister : product.costo_unidad);
        document.getElementById('cantidad').value = 0;
    }

    // Función para formatear el precio en moneda
    function formatCurrency(value) {
        return '$' + value.toLocaleString();
    }



    const codeInput = document.getElementById('codigo');
    const cantidadInput = document.getElementById('cantidad');
    const addProductBtn = document.getElementById('addProductBtn');

    // Agregar eventos de cambio a los campos de entrada
    cantidadInput.addEventListener('change', checkCantidad);
    codeInput.addEventListener('input', checkCodigo);

    function checkCantidad() {
        // Verificar si la cantidad es mayor que 0 para habilitar el botón
        if (parseInt(this.value) > 0 && codeInput.value.trim() !== '') {
            addProductBtn.disabled = false; // Activar el botón
        } else {
            addProductBtn.disabled = true; // Desactivar el botón si la cantidad es 0 o negativa
        }
    }

    function checkCodigo() {
        // Verificar si el código no está vacío para habilitar el botón
        if (this.value.trim() !== '' && parseInt(cantidadInput.value) > 0) {
            addProductBtn.disabled = false; // Activar el botón
        } else {
            addProductBtn.disabled = true; // Desactivar el botón si el código está vacío
        }
    }

    addProductBtn.addEventListener('click', function() {
    // Captura los valores de los campos de entrada
    const formaInput = document.getElementById('forma');
    const valorSeleccionadoForma = formaInput.value;

    const codigo = codeInput.value.trim();
    const cantidad = parseInt(cantidadInput.value);

    // Verifica si los valores son válidos
    if (codigo !== '' && cantidad > 0) {
        // Envía los valores mediante un evento Livewire
        Livewire.emit('agregarProductoCrearCombo', codigo, cantidad, valorSeleccionadoForma);

        // Limpia los campos después de enviar los valores (si es necesario)
        codeInput.value = '';
        cantidadInput.value = '';

        // Desactiva el botón después de enviar los valores (si es necesario)
        addProductBtn.disabled = true;

        // Restaura la opacidad y visibilidad del modal
        $('#tuModal').modal('hide'); // Cierra el modal
        $('.modal-backdrop').remove(); // Remueve el fondo oscuro
    }
});



</script>

@stop


