<div class="modal-dialog modal-xl" id="modal-create-producto">
    <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #17A2B8">
            <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de productos</strong> </h5>
            <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>
        </div>
        <div class="modal-body " style="background-color: #f5fbfb">
            <div class="row">


                <div class="col-lg-6">
                    <div class="row mb-3">
                        <div class="form-floating mt-2 col-6">
                            <input type="text"
                                class="form-control @if ($code == '') @else @error('code') is-invalid @else is-valid @enderror @endif"
                                id="code" name="code" wire:model.lazy="code" autocomplete="off">
                            <label for="codigo" id="for_code" name="for_code">Código *</label>
                            @error('code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mt-2 col-6">
                            <input type="text"
                                class="form-control  @if ($name == '') @else @error('name') is-invalid @else is-valid @enderror @endif"
                                id="name" name="name" placeholder="Nombre o descripción" wire:model.lazy="name"
                                autocomplete="off">
                            <label for="floatingInput">Nombre / Descripción *</label>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-floating mt-1 col-4">
                            <input type="number" class="form-control" id="stock_min" name="stock_min"
                                wire:model.defer="stock_min">
                            <label for="floatingInput">Stock mínimo *</label>
                            @error('stock_min')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mt-1 col-4">
                            <input type="number" class="form-control" id="stock_max" name="stock_max"
                                wire:model.defer="stock_max">
                            <label for="floatingInput">Stock máximo *</label>
                            @error('stock_max')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{--    <div class="form-floating mt-1 col-3">
                            <input type="number"
                                class="form-control  @if ($stock == '') @else @error('stock') is-invalid @else is-valid @enderror @endif"
                                id="stock" name="stock" wire:model.defer="stock" disabled>
                            <label for="floatingInput">Stock actual </label>
                            @error('stock')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div> --}}

                        <div class="form-floating mt-1 col-4">
                            <input type="number"
                                class="form-control  @if ($iva_product == 0) @else @error('iva_product') is-invalid @else is-valid @enderror @endif"
                                id="iva_product" name="iva_product" wire:model.defer="iva_product" min="0"
                                max="100">
                            <label for="floatingInput">% IVA </label>
                            @error('iva_product')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>




                    <div class="row mb-3" x-data="{ categorySelected: @entangle('category_id').defer }">

                        <div class="form-floating mt-1 col-4">
                            <select class="form-select" id="category_id" name="category_id"
                                aria-label="Floating label select example" wire:model.defer="category_id"
                                @change="categorySelected = $event.target.value">
                                @foreach ($categories as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelect" class="ml-2">Categoria *</label>
                            @error('category_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mt-1 col-4">
                            <select class="form-select" id="subcategory_id" name="subcategory_id"
                                aria-label="Floating label select example" wire:model.lazy="subcategory_id"
                                :disabled="!categorySelected">
                                @foreach ($subcategorias as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelect" class="ml-2">Subcategoría</label>
                            @error('subcategory_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-floating mt-4 col-4 row">

                            <div class="col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input mt-1" type="radio" name="active_si" id="active_si"
                                        value="ACTIVE" wire:model = "status">
                                    <label class="form-check-label" for="inlineRadio1">Activo</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input mt-1" type="radio" name="active_no"
                                        id="active_no" value="DESACTIVE" wire:model = "status">
                                    <label class="form-check-label" for="inlineRadio2">Inactivo</label>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="row mb-3">

                        <div class="form-floating mt-1 col-4">
                            <select class="form-select" id="laboratorio_id" name="laboratorio_id"
                                aria-label="Floating label select example" wire:model.lazy="laboratorio_id">
                                @foreach ($laboratorios as $laboratorio)
                                    <option value="{{ $laboratorio->id }}">{{ $laboratorio->name }}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelect" class="ml-2">Laboratorio *</label>

                            @error('laboratorio_id ')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-floating mt-1 col-4">
                            <select class="form-select" id="ubicacion_id" name="ubicacion_id"
                                aria-label="Floating label select example" wire:model.defer="ubicacion_id">
                                @foreach ($ubicaciones as $ubicacion)
                                    <option value="{{ $ubicacion->id }}">{{ $ubicacion->name }}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelect" class="ml-2">Ubicación</label>

                            @error('ubicacion_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mt-1 col-4">
                            <select class="form-select" id="presentacion_id" name="presentacion_id"
                                aria-label="Floating label select example" wire:model.defer="presentacion_id">
                                <option value="" selected>Seleccione una opción</option>
                                @foreach ($presentaciones as $pre)
                                    <option value="{{ $pre->id }}" data-presentacion="{{ json_encode($pre) }}">
                                        {{ $pre->name }}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelect" class="ml-2">Presentación *</label>

                            @error('presentacion_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mt-1 col-5 row">
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

                        <div class=" col-7">

                            <label for="formFile" class="form-label">Imagen Producto</label>
                            <input class="form-control" type="file" id="image" name="image"
                                wire:model="image">
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>


                </div>

                <div class="col-lg-6">

                    <div class="row mb-3">
                        <div class="form-floating mt-2 col-4">
                            <div class="form-floating">
                                <select class="form-select" id="disponible_caja" name="disponible_caja"
                                    aria-label="Floating label select example" wire:model.defer = 'disponible_caja'
                                    disabled>
                                    <option selected>Seleccione una opción</option>
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                                <label for="floatingSelect">Disp. caja</label>
                            </div>
                        </div>

                        <div class="form-floating mt-2 col-4">

                            <div class="form-floating">
                                <select class="form-select" id="disponible_blister" name="disponible_blister"
                                    disabled aria-label="Floating label select example"
                                    wire:model.defer = 'disponible_blister'>
                                    <option selected>Seleccione una opción</option>
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                                <label for="floatingSelect">Disp. blister</label>
                            </div>


                        </div>

                        <div class="form-floating mt-2 col-4">

                            <div class="form-floating">
                                <select class="form-select" id="disponible_unidad" name="disponible_unidad" disabled
                                    aria-label="Floating label select example" wire:model.defer = 'disponible_unidad'>
                                    <option selected>Seleccione una opción</option>
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                                <label for="floatingSelect">Disp. unidad</label>
                            </div>

                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="form-floating mt-2 col-4">
                            <input type="number"
                                class="form-control  @if ($contenido_interno_caja == '') @else @error('contenido_interno_caja') is-invalid @else is-valid @enderror @endif"
                                id="contenido_interno_caja" name="contenido_interno_caja" min="0"
                                wire:model.defer="contenido_interno_caja" disabled>
                            <label for="floatingInput">Cantidad caja </label>
                            @error('contenido_interno_caja')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mt-2 col-4">
                            <input type="number"
                                class="form-control  @if ($contenido_interno_blister == '') @else @error('contenido_interno_blister') is-invalid @else is-valid @enderror @endif"
                                id="contenido_interno_blister" name="contenido_interno_blister" value="0"
                                min="0" wire:model.defer="contenido_interno_blister" disabled>
                            <label for="floatingInput">Contenido blister </label>
                            @error('contenido_interno_blister')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mt-2 col-4">
                            <input type="number"
                                class="form-control  @if ($contenido_interno_unidad == '') @else @error('contenido_interno_unidad') is-invalid @else is-valid @enderror @endif"
                                id="contenido_interno_unidad" name="contenido_interno_unidad" value="0"
                                min="0" wire:model.defer="contenido_interno_unidad" disabled>
                            <label for="floatingInput">Contenido unidad </label>
                            @error('contenido_interno_unidad')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="form-floating mt-1 col-4">
                            <input type="number"
                                class="form-control  @if ($costo_caja == '') @else @error('costo_caja') is-invalid @else is-valid @enderror @endif"
                                id="costo_caja" name="costo_caja" value="0" min="0"
                                wire:model.defer="costo_caja" disabled oninput="limitarNumerosEnteros(this)">
                            <label for="floatingInput">Costo caja </label>
                            @error('costo_caja')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mt-1 col-4">
                            <input type="number" value="0" min="0"
                                class="form-control  @if ($costo_blister == '') @else @error('costo_blister') is-invalid @else is-valid @enderror @endif"
                                id="costo_blister" name="costo_blister" wire:model.defer="costo_blister" disabled
                                oninput="limitarNumerosEnteros(this)">
                            <label for="floatingInput">Costo blister </label>
                            @error('costo_blister')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mt-1 col-4">
                            <input type="number" value="0" min="0"
                                class="form-control  @if ($costo_unidad == '') @else @error('costo_unidad') is-invalid @else is-valid @enderror @endif"
                                id="costo_unidad" name="costo_unidad" wire:model.defer="contenido_interno_unidad"
                                disabled oninput="limitarNumerosEnteros(this)">
                            <label for="floatingInput">Costo unidad </label>
                            @error('costo_unidad')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="form-floating mt-1 col-4">
                            <input type="number" min="0"
                                class="form-control @if ($precio_caja == '') @else @error('precio_caja') is-invalid @else is-valid @enderror @endif"
                                id="precio_caja" name="precio_caja" wire:model.defer = 'precio_caja' disabled
                                oninput="limitarNumerosEnteros(this)">
                            <label for="floatingInput">Precio venta caja</label>
                            @error('precio_caja')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>



                        <div class="form-floating mt-1 col-4">
                            <input type="number" value="0" min="0"
                                class="form-control  @if ($precio_blister == '') @else @error('precio_blister') is-invalid @else is-valid @enderror @endif"
                                id="precio_blister" name="precio_blister" wire:model.defer="precio_blister" disabled
                                oninput="limitarNumerosEnteros(this)">
                            <label for="floatingInput">Precio venta blister </label>
                            @error('precio_blister')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mt-1 col-4">
                            <input type="number" value="0" min="0"
                                class="form-control  @if ($precio_unidad == '') @else @error('precio_unidad') is-invalid @else is-valid @enderror @endif"
                                id="precio_unidad" name="precio_unidad" wire:model.defer="precio_unidad" disabled
                                oninput="limitarNumerosEnteros(this)">
                            <label for="floatingInput">Precio venta unidad </label>
                            @error('precio_unidad')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            @if ($image)
                                <div class="mb-4"> <img style="height: 100px; width: 100px;"
                                        src="{{ $image->temporaryUrl() }}" alt="">
                                </div>
                            @endif
                        </div>
                        <div class="col-8 mt-5">
                            <button type="button" onclick="capturarYEnviarDatos()" id="guardarProductBtn"
                                class="btn btn-outline-success float-right ml-2 mb-1">Guardar Producto

                                <img class="loader" src="{{ asset('img/loading.gif') }}" alt="Cargando..."
                                    width="30px;" style="display: none;" />

                            </button>
                            <x-adminlte-button class="float-right" wire:click="cancel" theme="danger"
                                label="Cancelar" data-dismiss="modal" />
                        </div>
                    </div>


                </div>
            </div>
        </div>


    </div>

</div>
</div>
<script src="{{ asset('js/productos/validacionesProductCreate.js') }}"></script>
<script src="{{ asset('js/productos/calculoIva.js') }}"></script>
<script>
    window.addEventListener('alert', event => {

        Swal.fire(
            'Producto actualizado correctamente',
            '',
            'success'
        )
    })
</script>
<script>
    window.addEventListener('close-modal', event => {
        //alert('Hola mundo');
        $('#productomodal').hide();
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();


        location.reload();
    })
</script>

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
