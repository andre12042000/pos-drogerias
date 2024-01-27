<div class="modal-dialog modal-lg" theme="primary">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de Presentación</strong> </h5>
            <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>
        </div>
        @include('popper::assets')
        <div class="modal-body ">

            <div>
                @include('includes.alert')
                <div class="row">
                    <div class="col-lg-6">

                        <div class="form-group">
                            <label for="exampleFormControlInput1">Nombre de presentación</label>
                            <input type="text" wire:keydown.enter="storeOrupdate" wire:model.lazy="name" id="name" name="name" class="form-control @if($name == '') @else @error('name') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Ejemplo: CAJA" autocomplete="off" autofocus>

                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-12 ">Estado</label>
                            <div class="col-sm-8">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="active_si" id="active_si" value="ACTIVE" wire:model.lazy = "status">
                                    <label class="form-check-label" for="inlineRadio1">Activo</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="active_no" id="active_no" value="DESACTIVE" wire:model.lazy = "status">
                                    <label class="form-check-label" for="inlineRadio2">Inactivo</label>
                                  </div>
                                @error('active')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>




                    <div class="col-lg-4">
                <div class="form-group ">
                    <label for="exampleFormControlInput1">% Ganancia Caja</label>
                    <input type="number" wire:keydown.enter="storeOrupdate" wire:model.lazy="por_caja" id="por_caja" name="por_caja" class="form-control @if($por_caja == '') @else @error('por_caja') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Ejemplo: 30" autocomplete="off" autofocus>

                    @error('por_caja')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group col">
                    <label for="exampleFormControlInput1">% Ganancia Blister</label>
                    <input type="number" wire:keydown.enter="storeOrupdate" wire:model.lazy="por_blister" id="por_blister" name="por_blister" class="form-control @if($por_blister == '') @else @error('por_blister') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Ejemplo: 40" autocomplete="off" autofocus>

                    @error('por_blister')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
                <div class="col-lg-4">
                <div class="form-group">
                    <label for="exampleFormControlInput1">% Ganancia Unidad</label>
                    <input type="number" wire:keydown.enter="storeOrupdate" wire:model.lazy="por_unidad" id="por_unidad" name="por_unidad" class="form-control @if($por_unidad == '') @else @error('por_unidad') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Ejemplo: 50" autocomplete="off" autofocus>

                    @error('por_unidad')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                </div>
            </div>
                <button type="button" class="btn btn-success float-right ml-2" wire:click="storeOrupdate">Guardar</button>
                <x-adminlte-button class="float-right" wire:click="cancel" theme="danger" label="Cancelar" data-dismiss="modal" />

                <x-slot name="footerSlot" class="mt-0 mb-0 p-0">
                </x-slot>



            </div>
        </div>

    </div>
</div>

        <script>
            window.addEventListener('close-modal', event => {
                     //alert('Hola mundo');
                        $('#presentacionModal').hide();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    })
        </script>
