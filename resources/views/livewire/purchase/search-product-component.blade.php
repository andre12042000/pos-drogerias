<div class="modal-dialog modal-xl" theme="info">
    <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #17A2B8 ">
            <h5 class="modal-title " id="staticBackdropLabel"> <strong>Añadir productos a factura de compra</strong> </h5>
            <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>

        </div>
        @include('popper::assets')
        <div class="modal-body ">
            @include('includes.alert')

            <div x-data="{ busquedaAvanzada: false }">
                <div class="row">
                    <div class="col-8">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" placeholder="Buscar producto"
                                wire:model.lazy="search" wire:keydown.enter="buscar">


                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                    <!-- Icono de búsqueda (puedes usar otro icono o código HTML según tu preferencia) -->
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="busquedaAvanzadaInputCheck"
                                value="name" x-model="busquedaAvanzada" wire:model = 'tipoBusqueda'>
                            <label class="form-check-label" for="busquedaAvanzadaInputCheck">
                                Busqueda avanzada
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row ml-2 mt-2" x-show="busquedaAvanzada">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Nombre / Descripción</th>
                                <th scope="col">Laboratorio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($productos as $producto)
                                <tr wire:click="seleccionarProducto({{ $producto }})" style="cursor: pointer">
                                    <th scope="row" >{{ $producto->code }}</th>
                                    <td>{{ $producto->name }}</td>
                                    <td>{{ $producto->laboratorio->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td rowspan="4">
                                        <p><em>No hay registros disponibles...</em></p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                        {{ $productos->links() }}


                </div>
            </div>

            <div class="row mt-4">



                <input type="hidden" id="porcentaje_por_caja" name="porcentaje_por_caja" wire:model = 'porcentaje_por_caja' disabled>
                <input type="hidden" id="porcentaje_por_blister" name="porcentaje_por_blister" wire:model = 'porcentaje_por_blister' disabled>
                <input type="hidden" id="porcentaje_por_unidad" name="porcentaje_por_unidad" wire:model = 'porcentaje_por_unidad' disabled>


                <input type="hidden" id="input_disponible_blister" name="disponible_blister" wire:model = 'disponible_blister' disabled>
                <input type="hidden" id="input_disponible_unidad" name="disponible_unidad" wire:model = 'disponible_unidad' disabled>

                <input type="hidden" id="contenido_interno_blister_add" name="contenido_interno_blister_add" wire:model = 'contenido_interno_blister'>
                <input type="hidden" id="contenido_interno_unidad_add" name="contenido_interno_unidad_add" wire:model = 'contenido_interno_unidad'>





                <div class="row">
                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" wire:model.lazy = 'code' id="code"
                                placeholder="name@example.com" disabled>
                            <label for="floatingInput">Código</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" wire:model = 'name' id="name_add" name="name_add"
                                placeholder="name@example.com" disabled>
                            <label for="floatingInput">Nombre / descripción</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating">
                            <select class="form-select" wire:model.lazy = 'laboratorio_id' id="laboratorio_id"
                                aria-label="Floating label select example" disabled>
                                <option value="">Seleccione una opción</option>
                                @forelse ($laboratorios as $laboratorio)
                                    <option value="{{ $laboratorio->id }}">{{ $laboratorio->name }}</option>
                                @empty
                                    <option value="2">No hay datos disponibles</option>
                                @endforelse
                            </select>
                            <label for="floatingSelect">Laboratorios</label>
                        </div>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" wire:model.defer = 'lote' id="lote"
                                id="lote" placeholder="name@example.com" disabled>
                            <label for="floatingInput">Lote</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control" wire:model.defer = 'fecha_vencimiento' id="fecha_vencimiento"
                                id="fecha_nacimiento" placeholder="name@example.com" disabled>
                            <label for="floatingInput">Fecha de vencimiento</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" wire:model.defer = 'cantidad' id="cantidad"
                                placeholder="name@example.com" min="1" disabled>
                            <label for="floatingInput">Cantidad</label>
                        </div>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-4">

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" wire:model.defer = 'precio_compra' id="precio_compra"
                                placeholder="name@example.com" disabled>
                            <label for="floatingInput">Precio de compra</label>
                        </div>


                    </div>
                    <div class="col-4">

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" wire:model.defer = 'iva' id="iva" min='0' max="99"
                                placeholder="name@example.com" disabled>
                            <label for="floatingInput">Iva</label>
                        </div>

                    </div>
                    <div class="col-4">

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" wire:model.defer = 'descuento' id="descuento" min="0" max="99"
                                placeholder="name@example.com" disabled value="0" min="0">
                            <label for="floatingInput">Descuento</label>
                        </div>

                    </div>
                </div>

                <div class="row mt-1">

                    <div class="col-4">

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" wire:model.defer = 'precio_venta_caja' id="precio_venta_caja"
                                placeholder="name@example.com" disabled min="1">
                            <label for="floatingInput">Precio de venta caja</label>
                        </div>


                    </div>

                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" wire:model.defer = 'precio_venta_blister' id="precio_venta_blister"
                                placeholder="name@example.com" disabled min="0">
                            <label for="floatingInput">Precio de venta blister</label>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" wire:model.defer = 'precio_venta_unidad' id="precio_venta_unidad"
                                placeholder="name@example.com" disabled min="0">
                            <label for="floatingInput">Precio de venta unidad</label>
                        </div>
                    </div>

                </div>

                <div class="row mt-1">

                    <div class="col-4">

                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="obsequio" value="1">
                            <label class="form-check-label" for="busquedaAvanzadaInputCheck">
                                ¡Ingresar como obsequio!
                            </label>
                        </div>

                    </div>

                    <div class="col-4">
                    </div>

                    <div class="col-4">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-success btn-lg" type="button" id="agregarProductoBtn" disabled>Agregar producto</button>
                          </div>

                    </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">

        </div>
    </div>
</div>

@section('js')

<script src="{{ asset('js/compras/addProduct.js') }}"></script>

@stop









