<div class="modal-dialog" theme="primary">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de motivos anualción</strong> </h5>
            <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>
        </div>
        @include('popper::assets')
        <div class="modal-body ">

            <div>
                @include('includes.alert')
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre</label>
                    <input type="text" wire:keydown.enter="storeOrupdate" wire:model.lazy="name" id="name" name="name" class="form-control @if($name == '') @else @error('name') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Ejemplo: Vencimiento" autocomplete="off" autofocus>
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-lg-12">
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
                            @error('status')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-success float-right ml-2"  wire:click="storeOrupdate">Guardar</button>
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
                        $('#motivosModal').hide();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    })
        </script>
