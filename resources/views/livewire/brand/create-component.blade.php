<div class="modal-dialog" theme="primary">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de
                    marcas</strong> </h5>
            <button type="button" class="btn-close float-end" data-dismiss="modal" wire:click="cancel"
                aria-label="Close"></button>
        </div>
        @include('popper::assets')
        <div class="modal-body ">
            <div>
                @include('includes.alert')

                <div class="form-group py-3">

                    <label for="exampleFormControlInput1">Nombre de la marca</label>
                    <input type="text" wire:keydown.enter="storeOrupdate" wire:model.lazy="name"
                        class="form-control @if($name == '') @else @error('name') is-invalid @else is-valid @enderror @endif"
                        id="name" name="name" placeholder="Ejemplo: Yamaha, Honda, Susuki" autocomplete="off" autofocus>

                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror


                </div>

                <button type="button" class="btn btn-outline-success float-right ml-2"
                    wire:click="storeOrupdate">Guardar</button>
                <x-adminlte-button class="float-right" wire:click="cancel" theme="danger" label="Cancelar"
                    data-dismiss="modal" />

                <x-slot name="footerSlot" class="mt-0 mb-0 p-0">
                </x-slot>

            </div>
        </div>
    </div>
</div>
<!--  script de alerta  -->
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    window.addEventListener('close-modal', event => {
                     //alert('Hola mundo');
                        $('#brandmodal').hide();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    })
</script>
