<div>
    <div class="modal-body " style="background-color: #f5fbfb">
        <div class="container">
            <div class="row align-items-start">
                <div class="col">

                    <input type="hidden" class="form-control" id="id_product_edit" name="inputId"
                        placeholder="name@example.com">

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="code_edit" name="inputCodigo"
                                    placeholder="name@example.com" wire:model.defer = 'code_edit'>
                                <label for="floatingInput">Código</label>
                                @error('code_edit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name_edit" name="name_edit"
                                    placeholder="name@example.com" wire:model.defer = 'name_edit'>
                                <label for="floatingInput">Nombre / Descripción</label>
                                @error('name_edit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="row ">
                        <div class="col">
                            <div class="form-floating  mb-3">
                                <select class="form-select" id="categoria_edit" name="categoria_edit"
                                    wire:model = "categoria_id" aria-label="Floating label select example">
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ ucwords($categoria->name) }}</option>
                                    @endforeach
                                </select>
                                <label for="floatingSelect">Categoría</label>
                                @error('categoria_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="subcategoria_edit" name="subcategoria_edit"
                                    aria-label="Floating label select example" wire:model = "subcategoria_id">
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($subcategorias as $subcategoria)
                                        <option value="{{ $subcategoria->id }}">{{ ucwords($subcategoria->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="floatingSelect">Subcategoría</label>
                                @error('subcategoria_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="row ">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="ubicaciones_edit" name="ubicaciones_edit"
                                    aria-label="Floating label select example" wire:model = 'ubicacion_id'>
                                    @foreach ($ubicaciones as $ubicacion)
                                        <option value="{{ $ubicacion->id }}">{{ ucwords($ubicacion->name) }}</option>
                                    @endforeach
                                </select>
                                <label for="floatingSelect">Ubicación</label>
                                @error('ubicacion_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="presentaciones_edit" name="presentaciones_edit"
                                    aria-label="Floating label select example" wire:model = 'presentacion_id'>
                                    @foreach ($presentaciones as $presentacion)
                                        <option value="{{ $presentacion->id }}">{{ ucwords($presentacion->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="floatingSelect">Presentación</label>
                                @error('presentacion_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row ">


                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="laboratorios_select" name="laboratorios_select"
                                    aria-label="Floating label select example" wire:model = 'laboratorio_id'>
                                    @foreach ($laboratorios as $laboratorio)
                                        <option value="{{ $laboratorio->id }}">{{ ucwords($laboratorio->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="floatingSelect">Laboratorio</label>
                                @error('laboratorio_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="iva_edit" name="iva_edit" value="0"
                                    placeholder="name@example.com" wire:model.defer = 'iva_edit'>
                                <label for="floatingInput">% IVA</label>
                                @error('iva_edit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="stock_minimo_edit"
                                    placeholder="name@example.com" value="0" min="0"
                                    wire:model.defer = 'stock_minimo_edit'>
                                <label for="floatingInput">Stock mínimo </label>
                                @error('stock_minimo_edit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="stock_maximo_edit"
                                    placeholder="name@example.com" value="0" min="0"
                                    wire:model.defer = 'stock_maximo_edit'>
                                <label for="floatingInput">Stock máximo</label>
                                @error('stock_maximo_edit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class=" col-6">


                            <label>Imagen Producto</label>
                            <input class="form-control" type="file" id="image" name="image"
                                wire:model="image">


                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6">
                            @if($photo)
                            <div class="mb-4"> <img style="height: 100px; width: 100px;" src="/storage/{{ $photo }}" alt="">
                            </div>

                            @elseif($image)
                            <div class="mb-4"> <img style="height: 100px; width: 100px;" src="{{ $image->temporaryUrl() }}" alt="">
                            </div>

                            @endif
                        </div>


                    </div>

                </div>
                <div class="col">
                    <div class="row mb-3">
                        <div class="form-floating  col-4">
                            <div class="form-floating">
                                <select class="form-select" id="disponible_caja_edit" name="disponible_caja_edit"
                                    disabled aria-label="Floating label select example">
                                    <option value="1" selected>Si</option>
                                    <option value="0">No</option>
                                </select>
                                <label for="floatingSelect">Disp. caja</label>
                            </div>
                        </div>

                        <div class="form-floating  col-4">

                            <div class="form-floating">
                                <select class="form-select" id="disponible_blister_edit" name="disponible_caja_edit"
                                    aria-label="Floating label select example" wire:model = 'disponible_blister_edit'>
                                    <option value="Seleccionar">Seleccione una opción</option>
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                                <label for="floatingSelect">Disp. blister</label>
                                @error('disponible_blister_edit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>

                        <div class="form-floating  col-4">

                            <div class="form-floating">
                                <select class="form-select" id="disponible_unidad_edit" name="disponible_unidad_edit"
                                    aria-label="Floating label select example" wire:model = 'disponible_unidad_edit'>
                                    <option value="Seleccionar">Seleccione una opción</option>
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                                <label for="floatingSelect">Disp. unidad</label>
                                @error('disponible_unidad_edit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="row ">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="hidden" class="form-control" id="puntoReferenciaCaja"
                                    name="puntoReferenciaCaja" placeholder="name@example.com" value="1"
                                    disabled>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="blister_por_caja_edit"
                                    name="blister_por_caja_edit" placeholder="name@example.com" {{ $estado_blister }}
                                    wire:model.defer = 'blister_por_caja_edit'>
                                <label for="floatingInput">Blister * caja</label>
                                @error('blister_por_caja_edit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="unidad_por_caja_edit"
                                    name="unidad_por_caja_edit" placeholder="name@example.com" {{ $estado_unidad }}
                                    wire:model.defer = 'unidad_por_caja_edit'>
                                <label for="floatingInput">Unidades * caja</label>
                            </div>
                        </div>
                    </div>





                    <div class="row ">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="CostoPorCajaEdit"
                                    name="inputCostoPorCaja" placeholder="name@example.com" min="0"
                                    value="0" wire:model.lazy = 'CostoPorCajaEdit'
                                    oninput="limitarNumerosEnteros(this)">
                                <label for="floatingInput">Costo caja</label>
                                @error('CostoPorCajaEdit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="CostoPorBlisterEdit"
                                    name="CostoPorBlisterEdit" placeholder="name@example.com" min="0"
                                    value="0" {{ $estado_blister }} wire:model.lazy = 'CostoPorBlisterEdit'
                                    oninput="limitarNumerosEnteros(this)">
                                <label for="floatingInput">Costo blister</label>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="CostoPorUnidadEdit"
                                    name="inputCostoPorUnidad" placeholder="name@example.com" min="0"
                                    value="0" {{ $estado_unidad }} wire:model.lazy = 'CostoPorUnidadEdit'
                                    oninput="limitarNumerosEnteros(this)">
                                <label for="floatingInput">Costo unidad</label>
                                @error('CostoPorUnidadEdit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row ">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="PrecioVentaCajaEdit"
                                    name="PrecioVentaCajaEdit" placeholder="name@example.com" min="0"
                                    value="0" wire:model = 'PrecioVentaCajaEdit'
                                    oninput="limitarNumerosEnteros(this)">
                                <label for="floatingInput">Precio venta caja</label>
                                @error('PrecioVentaCajaEdit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="PrecioVentaBlisterEdit"
                                    name="PrecioVentaBlisterEdit" placeholder="name@example.com" min="0"
                                    value="0" {{ $estado_blister }} wire:model.lazy = 'PrecioVentaBlisterEdit'
                                    oninput="limitarNumerosEnteros(this)">
                                <label for="floatingInput">Precio venta blister</label>
                                @error('PrecioVentaBlisterEdit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="PrecioVentaUnidadEdit"
                                    name="PrecioVentaUnidadEdit" placeholder="name@example.com" min="0"
                                    value="0" {{ $estado_unidad }} wire:model.lazy = 'PrecioVentaUnidadEdit'
                                    oninput="limitarNumerosEnteros(this)">
                                <label for="floatingInput">Precio venta unidad</label>
                                @error('PrecioVentaUnidadEdit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row ">
                        <div class="form-floating row col-4 mt-4">
                            <div class="col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input mt-1" type="radio" name="EstatusProductEdit"
                                        id="estado_produc_active" value="ACTIVE" wire:model.defer = 'status'>
                                    <label class="form-check-label" for="inlineRadio1">Activo</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input mt-1" type="radio" name="EstatusProductEdit"
                                        id="estado_produc_inactive" value="DESACTIVE" wire:model.defer = 'status'>
                                    <label class="form-check-label" for="inlineRadio2">Inactivo</label>
                                </div>
                            </div>


                        </div>


                        <div class="col-5 ml-4">
                            <div class=" mt-1 row">
                                <strong>¿Es Materia Prima?</strong>
                                <div class="col-lg-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input mt-1" type="radio" id="is_materia_prima"
                                            name="is_materia_prima" value="si" wire:model = "is_materia_prima">
                                        <label class="form-check-label" for="inlineRadio1">Si</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input mt-1" type="radio" id="is_materia_prima"
                                            name="is_materia_prima" value="no" wire:model = "is_materia_prima">
                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="col-12 mt-5">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-success float-end mt-4" id="btnActualizar"
                                wire:click="actualizarProduct" wire:loading.attr="disabled"
                                wire:loading.class="btn-secondary"
                                wire:loading.class.remove="btn-outline-success">
                                Actualizar

                                <div wire:loading wire:target="actualizarProduct">
                                    <img src="{!! Config::get('app.URL') !!}/assets/img/loading.gif" width="20px"
                                        class="img-fluid" alt="">
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function limitarNumerosEnteros(input) {
        input.addEventListener('input', function(event) {
            this.value = this.value.replace(/[^\d]/g,
            ''); // Reemplaza cualquier cosa que no sea dígito por nada
        });

        input.addEventListener('keydown', function(event) {
            // Permitir las teclas de navegación (flechas, inicio, fin, retroceso)
            if (event.key === "ArrowLeft" || event.key === "ArrowRight" || event.key === "ArrowUp" || event
                .key === "ArrowDown" ||
                event.key === "Home" || event.key === "End" || event.key === "Backspace") {
                return;
            }
            // Prevenir la acción predeterminada si la tecla presionada no es un número
            if (isNaN(parseInt(event.key))) {
                event.preventDefault();
            }
        });

    }
</script>

<script>
    // document.addEventListener('DOMContentLoaded', function () {
    //     // Función para actualizar el precio de venta en un 30% del costo
    //     function updatePrice(costInputId, priceInputId) {


    //         const costInput = document.getElementById(costInputId);
    //         const priceInput = document.getElementById(priceInputId);

    //         if (costInput && priceInput) {
    //             costInput.addEventListener('input', function () {
    //                 const cost = parseFloat(costInput.value);
    //                 if (!isNaN(cost)) {
    //                     const price = cost * 1.30;
    //                     priceInput.value = price.toFixed(0); // Redondear a 2 decimales
    //                 } else {
    //                     priceInput.value = '';
    //                 }
    //             });
    //         }
    //     }

    //     // Asociar los eventos a cada par de costo/precio
    //     updatePrice('CostoPorCajaEdit', 'PrecioVentaCajaEdit');
    //     updatePrice('CostoPorBlisterEdit', 'PrecioVentaBlisterEdit');
    //     updatePrice('CostoPorUnidadEdit', 'PrecioVentaUnidadEdit');
    // });
</script>

