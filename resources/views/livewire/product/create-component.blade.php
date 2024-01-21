<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header bg-info">
            <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de productos</strong> </h5>
            <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>
        </div>
        <div class="modal-body " style="background-color: #f5fbfb">

            <div class="row">
                <div class="col-lg-6 " >

                    <div class="row">
                        <div class="form-floating mb-1 mt-2 col-6">
                            <input type="text"
                                class="form-control @if ($code == '') @else @error('code') is-invalid @else is-valid @enderror @endif"
                                id="code" name="code" placeholder="Código del producto" wire:model.lazy="code">
                            <label for="floatingInput">Código</label>
                            @error('code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mb-1 mt-2 col-6">
                            <input type="text"
                                class="form-control  @if ($name == '') @else @error('name') is-invalid @else is-valid @enderror @endif"
                                id="name" name="name" placeholder="Código del producto" wire:model.lazy="name">
                            <label for="floatingInput">Nombre </label>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mb-1  col-3">
                            <input type="number"
                                class="form-control  @if ($stock == '') @else @error('stock') is-invalid @else is-valid @enderror @endif"
                                id="stock" name="stock" placeholder="Código del producto" wire:model.lazy="stock">
                            <label for="floatingInput">Stock </label>
                            @error('stock')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mb-1  col-3">
                            <input type="number"
                                class="form-control  @if ($stock_min == '') @else @error('stock_min') is-invalid @else is-valid @enderror @endif"
                                id="stock_min" name="stock_min" placeholder="Código del producto" wire:model.lazy="stock_min">
                            <label for="floatingInput">Stock mínimo </label>
                            @error('stock_min')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mb-1  col-3">
                            <input type="number"
                                class="form-control  @if ($stock_max == '') @else @error('stock_max') is-invalid @else is-valid @enderror @endif"
                                id="stock_max" name="stock_max" placeholder="Código del producto" wire:model.lazy="stock_max">
                            <label for="floatingInput">Stock máximo </label>
                            @error('stock_max')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mb-1  col-3">
                            <input type="number"
                                class="form-control  @if ($iva_product == '') @else @error('iva_product') is-invalid @else is-valid @enderror @endif"
                                id="iva_product" name="iva_product" placeholder="Código del producto" wire:model.lazy="iva_product">
                            <label for="floatingInput">% IVA </label>
                            @error('iva_product')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-floating col-6 mb-1">
                            <select
                                class="form-select @if ($ubicacion_id == '') @else @error('ubicacion_id') is-invalid @else is-valid @enderror @endif"
                                id="ubicacion_id" name="ubicacion_id" aria-label="Floating label select example"
                                wire:model.lazy="ubicacion_id">
                                <option selected>Seleccionar </option>
                                @foreach ($ubicaciones as $ubicacion)
                                    <option value="{{ $ubicacion->id }}">{{ $ubicacion->name }}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelect" class="ml-2">Ubicación</label>

                            @error('ubicacion_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-floating col-6 mb-1">
                            <select
                                class="form-select @if ($presentacion_id == '') @else @error('presentacion_id') is-invalid @else is-valid @enderror @endif"
                                id="presentacion_id" name="presentacion_id" aria-label="Floating label select example"
                                wire:model.lazy="presentacion_id">
                                <option selected>Seleccionar </option>
                                @foreach ($presentaciones as $pre)
                                    <option value="{{ $pre }}">{{ $pre->name }}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelect" class="ml-2">Presentación</label>

                            @error('presentacion_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating col-6 mb-1">
                            <select
                                class="form-select @if ($category_id == '') @else @error('category_id') is-invalid @else is-valid @enderror @endif"
                                id="category_id" name="category_id" aria-label="Floating label select example"
                                wire:model.lazy="category_id">
                                <option selected>Seleccionar </option>
                                @foreach ($categories as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelect" class="ml-2">Categorias</label>

                            @error('category_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating col-6 mb-1">
                            <select
                                class="form-select @if ($subcategory_id == '') @else @error('subcategory_id') is-invalid @else is-valid @enderror @endif"
                                id="subcategory_id" name="subcategory_id" aria-label="Floating label select example"
                                wire:model.lazy="subcategory_id"  @if ($category_id == '')    disabled   @endif>
                                <option selected>Seleccionar </option>
                                @foreach ($subcategorias as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelect" class="ml-2">Sub-categorias</label>

                            @error('subcategory_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mb-1  col-3">
                            <input type="number"
                                class="form-control  @if ($last_price == '') @else @error('last_price') is-invalid @else is-valid @enderror @endif"
                                id="last_price" name="last_price" placeholder="Código del producto" wire:model.lazy="last_price">
                            <label for="floatingInput">Precio compra </label>
                            @error('last_price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-floating mb-1  col-3">
                            <input type="number"
                                class="form-control  @if ($sell_price == '') @else @error('sell_price') is-invalid @else is-valid @enderror @endif"
                                id="sell_price" name="sell_price" placeholder="Código del producto" wire:model.lazy="sell_price">
                            <label for="floatingInput">Precio caja </label>
                            @error('sell_price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>



                        <div class="form-floating mb-1  col-3">
                            <input type="number"
                                class="form-control  @if ($sell_price_tecnico == '') @else @error('sell_price_tecnico') is-invalid @else is-valid @enderror @endif"
                                id="sell_price_tecnico" name="sell_price_tecnico"  placeholder="Código del producto"
                                wire:model.lazy="sell_price_tecnico">
                            <label for="floatingInput">Precio blister </label>
                            @error('sell_price_tecnico')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-floating mb-1 col-3">
                            <input type="number"
                                class="form-control  @if ($sell_price_distribuidor == '') @else @error('sell_price_distribuidor') is-invalid @else is-valid @enderror @endif"
                                id="sell_price_distribuidor" name="sell_price_distribuidor" placeholder="Código del producto"
                                wire:model.lazy="sell_price_distribuidor">
                            <label for="floatingInput">Precio unidad </label>
                            @error('sell_price_distribuidor')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>




                    </div>



                </div>
                <div class="col-lg-6" >
                    <div class="row">
                        <div class="form-floating  mt-2 col-4">
                            <input type="number"
                                class="form-control  @if ($contenido_interno_caja == '') @else @error('contenido_interno_caja') is-invalid @else is-valid @enderror @endif"
                                id="contenido_interno_caja" name="contenido_interno_caja" placeholder="Código del producto"
                                wire:model.lazy="contenido_interno_caja">
                            <label for="floatingInput">Contenido caja </label>
                            @error('contenido_interno_caja')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating  mt-2 col-4">
                            <input type="number"
                                class="form-control  @if ($contenido_interno_blister == '') @else @error('contenido_interno_blister') is-invalid @else is-valid @enderror @endif"
                                id="contenido_interno_blister" name="contenido_interno_blister" placeholder="Código del producto"
                                wire:model.lazy="contenido_interno_blister">
                            <label for="floatingInput">Contenido blister </label>
                            @error('contenido_interno_blister')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating  mt-2 col-4">
                            <input type="number"
                                class="form-control  @if ($contenido_interno_unidad == '') @else @error('contenido_interno_unidad') is-invalid @else is-valid @enderror @endif"
                                id="contenido_interno_unidad" name="contenido_interno_unidad" placeholder="Código del producto"
                                wire:model.lazy="contenido_interno_unidad">
                            <label for="floatingInput">Contenido unidad </label>
                            @error('contenido_interno_unidad')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating  mt-1 col-4">
                            <input type="number"
                                class="form-control  @if ($costo_caja == '') @else @error('costo_caja') is-invalid @else is-valid @enderror @endif"
                                id="costo_caja" name="costo_caja" placeholder="Código del producto" wire:model.lazy="costo_caja">
                            <label for="floatingInput">Costo caja </label>
                            @error('costo_caja')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating  mt-1 col-4">
                            <input type="number"
                                class="form-control  @if ($costo_blister == '') @else @error('costo_blister') is-invalid @else is-valid @enderror @endif"
                                id="costo_blister" name="costo_blister" placeholder="Código del producto" wire:model.lazy="costo_blister">
                            <label for="floatingInput">Costo blister </label>
                            @error('costo_blister')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating  mt-1 col-4">
                            <input type="number"
                                class="form-control  @if ($costo_unidad == '') @else @error('costo_unidad') is-invalid @else is-valid @enderror @endif"
                                id="costo_unidad" name="costo_unidad" placeholder="Código del producto"
                                wire:model.lazy="contenido_interno_unidad">
                            <label for="floatingInput">Costo unidad </label>
                            @error('costo_unidad')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mt-1  col-3">
                            <input type="number"
                                class="form-control  @if ($exento == '') @else @error('exento') is-invalid @else is-valid @enderror @endif"
                                id="extento" name="extento" placeholder="Código del producto" wire:model.lazy="exento">
                            <label for="floatingInput">Exento </label>
                            @error('exento')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-floating mt-1 col-3">
                            <input type="number"
                                class="form-control  @if ($excluido == '') @else @error('excluido') is-invalid @else is-valid @enderror @endif"
                                id="excluido" name="excluido" placeholder="Código del producto" wire:model.lazy="excluido">
                            <label for="floatingInput">Excluido </label>
                            @error('excluido')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-floating mt-1 col-3">
                            <input type="number"
                                class="form-control  @if ($no_gravado == '') @else @error('no_gravado') is-invalid @else is-valid @enderror @endif"
                                id="no_gravado" name="no_gravado" placeholder="Código del producto" wire:model.lazy="name">
                            <label for="floatingInput">No gravado </label>
                            @error('no_gravado')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-floating mt-1 col-3">
                            <input type="number"
                                class="form-control  @if ($gravado == '') @else @error('gravado') is-invalid @else is-valid @enderror @endif"
                                id="gravado" name="gravado" placeholder="Código del producto" wire:model.lazy="gravado">
                            <label for="floatingInput">Gravado </label>
                            @error('gravado')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-floating col-6 mt-1">
                            <select
                                class="form-select @if ($laboratorio_id == '') @else @error('laboratorio_id ') is-invalid @else is-valid @enderror @endif"
                                id="laboratorio_id" name="laboratorio_id" aria-label="Floating label select example"
                                wire:model.lazy="presentacion_id">
                                <option selected>Seleccionar </option>
                                @foreach ($laboratorios as $laboratorio)
                                    <option value="{{ $laboratorio->id }}">{{ $laboratorio->name }}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelect" class="ml-2">Laboratorio</label>

                            @error('laboratorio_id ')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-6 ">
                            <label for="staticEmail" class="col-sm-4 col-form-label">Estado</label>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="active_si"
                                            id="active_si" value="ACTIVE" wire:model.lazy = "status">
                                        <label class="form-check-label" for="inlineRadio1">Activo</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="active_no"
                                            id="active_no" value="DESACTIVE" wire:model.lazy = "status">
                                        <label class="form-check-label" for="inlineRadio2">Inactivo</label>
                                    </div>
                                </div>
                            </div>

                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>

                    <button type="button" class="btn btn-success float-right ml-2 mb-1"
                    wire:click="storeOrupdate">Guardar</button>
                <x-adminlte-button class="float-right" wire:click="cancel" theme="danger" label="Cancelar"
                    data-dismiss="modal" />
                </div>
            </div>





        </div>
    </div>

</div>
</div>
<script src="{{ asset('js/productos/validacionesProduct.js')}}"></script>
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
    })
</script>
