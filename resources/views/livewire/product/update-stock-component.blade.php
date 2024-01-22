<div class="modal-dialog modal-lg" theme="primary">
    <div class="modal-content">
        <div class="modal-header bg-warning">
            <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de Stock</strong> </h5>
            <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>
        </div>
        @include('popper::assets')
        <div class="modal-body ">

            <div>
                @include('includes.alert')


                <div class="row mb-3">
                    <div class="form-floating mt-2 col-4">
                        <div class="form-floating">
                            <select class="form-select"  id="disponible_caja" name="disponible_caja"
                                aria-label="Floating label select example" wire:model.defer = 'disponible_caja' >
                                <option selected>Seleccione una opción</option>
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                            <label for="floatingSelect">Disp. caja</label>
                        </div>
                    </div>

                    <div class="form-floating mt-2 col-4">

                        <div class="form-floating">
                            <select class="form-select"  id="disponible_blister" name="disponible_blister"
                                aria-label="Floating label select example" wire:model.defer = 'disponible_blister'>
                                <option selected>Seleccione una opción</option>
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                            <label for="floatingSelect">Disp. blister</label>
                        </div>


                    </div>

                    <div class="form-floating mt-2 col-4">

                        <div class="form-floating">
                            <select class="form-select"  id="disponible_unidad" name="disponible_unidad"
                                aria-label="Floating label select example"  wire:model.defer = 'disponible_unidad'>
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
                            id="contenido_interno_caja" name="contenido_interno_caja"
                            min="0" wire:model.defer="contenido_interno_caja" >
                        <label for="floatingInput">Cantidad caja </label>
                        @error('contenido_interno_caja')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-floating mt-2 col-4">
                        <input type="number"
                            class="form-control  @if ($contenido_interno_blister == '') @else @error('contenido_interno_blister') is-invalid @else is-valid @enderror @endif"
                            id="contenido_interno_blister" name="contenido_interno_blister" value="0"
                            min="0" wire:model.defer="contenido_interno_blister" >
                        <label for="floatingInput">Contenido blister </label>
                        @error('contenido_interno_blister')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-floating mt-2 col-4">
                        <input type="number"
                            class="form-control  @if ($contenido_interno_unidad == '') @else @error('contenido_interno_unidad') is-invalid @else is-valid @enderror @endif"
                            id="contenido_interno_unidad" name="contenido_interno_unidad" value="0"
                            min="0" wire:model.defer="contenido_interno_unidad" >
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
                            wire:model.defer="costo_caja" >
                        <label for="floatingInput">Costo caja </label>
                        @error('costo_caja')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-floating mt-1 col-4">
                        <input type="number" value="0" min="0"
                            class="form-control  @if ($costo_blister == '') @else @error('costo_blister') is-invalid @else is-valid @enderror @endif"
                            id="costo_blister" name="costo_blister" wire:model.defer="costo_blister" >
                        <label for="floatingInput">Costo blister </label>
                        @error('costo_blister')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-floating mt-1 col-4">
                        <input type="number" value="0" min="0"
                            class="form-control  @if ($costo_unidad == '') @else @error('costo_unidad') is-invalid @else is-valid @enderror @endif"
                            id="costo_unidad" name="costo_unidad" wire:model.defer="contenido_interno_unidad"
                            >
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
                            id="precio_caja" name="precio_caja" wire:model.defer = 'precio_caja' >
                        <label for="floatingInput">Precio venta caja</label>
                        @error('precio_caja')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>



                    <div class="form-floating mt-1 col-4">
                        <input type="number" value="0" min="0"
                            class="form-control  @if ($precio_blister == '') @else @error('precio_blister') is-invalid @else is-valid @enderror @endif"
                            id="precio_blister" name="precio_blister" wire:model.defer="precio_blister" >
                        <label for="floatingInput">Precio venta blister </label>
                        @error('precio_blister')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-floating mt-1 col-4">
                        <input type="number" value="0" min="0"
                            class="form-control  @if ($precio_unidad == '') @else @error('precio_unidad') is-invalid @else is-valid @enderror @endif"
                            id="precio_unidad" name="precio_unidad" wire:model.defer="precio_unidad" >
                        <label for="floatingInput">Precio venta unidad </label>
                        @error('precio_unidad')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <button type="button" class="btn btn-success float-right ml-2"
                    wire:click="actualizarstock">Guardar</button>
                <x-adminlte-button class="float-right" wire:click="cancel" theme="danger" label="Cancelar"
                    data-dismiss="modal" />

                <x-slot name="footerSlot" class="mt-0 mb-0 p-0">
                </x-slot>



            </div>
        </div>

    </div>
</div>
<script src="{{ asset('js/productos/validacionesProductCreate.js') }}"></script>

<script>
    window.addEventListener('close-modal', event => {
        //alert('Hola mundo');
        $('#stockModal').hide();
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    })
</script>
