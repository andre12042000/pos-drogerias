<div class="modal-dialog modal-dialog-scrollable" theme="primary">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de rol</strong> </h5>
            <button type="button" class="btn-close" data-dismiss="modal" wire:click="cleanData"
                aria-label="Close"></button>
        </div>
        @include('popper::assets')
        <div class="modal-body ">
            <div>
                @include('includes.alert')
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre del rol</label>
                    <input type="text" wire:keydown.enter="storeOrupdate" wire:model.lazy="name" id="name"
                        name="name"
                        class="form-control @if ($name == '') @else @error('name') is-invalid @else is-valid @enderror @endif"
                        id="exampleFormControlInput1" placeholder="Ejemplo: Cajero, Tecnico, Vendedor">

                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group row">
                    <label class="mt-3">Listado de permisos</label>
                    <div class="col-sm-8">
                        @foreach ($permissions as $permission)
                            <div class="d-flex py-2">
                                <input type="checkbox" wire:model="permission_rol" value="{{ $permission->id }}"
                                    class="d-inline-block mr-2" />
                                &nbsp;
                                &nbsp; {{ $permission->name }}
                            </div>
                        @endforeach
                        @error('permission_rol')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success float-right ml-2 py-2"
                wire:click="storeOrupdate">Guardar</button>
            <x-adminlte-button class="float-right py-2" wire:click="cleanData" theme="danger" label="Cancelar"
                data-dismiss="modal" />

            <x-slot name="footerSlot" class="mt-0 mb-0 p-0">
            </x-slot>
        </div>

    </div>
</div>
<script>
    window.addEventListener('alert', event => {

        Swal.fire(
            'Rol actualizado correctamente',
            '',
            'success'
        )
    })
</script>
<script>
    window.addEventListener('close-modal', event => {
        //alert('Hola mundo');
        $('#rolemodal').hide();
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    })
</script>
