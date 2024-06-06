<div class="modal-dialog" theme="primary">
  <div class="modal-content">
    <div class="modal-header bg-primary">
      <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de proveedor</strong> </h5>
      <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>
    </div>

    @include('popper::assets')
    <div class="modal-body ">

        <div class="row mr-1 ml-1">
            @include('includes.alert')
        </div>

      <div>
        <div class="form-group row">
          <label for="staticEmail" class="col-sm-4 col-form-label">Nit</label>
          <div class="col-sm-8">
            <input type="text" wire:model.lazy="nit" id="nit" name="nit" class="form-control @if($nit == '') @else @error('nit') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Número de identificación tributaria" autocomplete="off" autofocus>
            @error('nit')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <label for="staticEmail" class="col-sm-4 col-form-label">Razón social<span class="fs-6 text-danger ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
          <div class="col-sm-8">
            <input type="text" wire:model.lazy="name" class="form-control @if($name == '') @else @error('name') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Nombre o razón social" autocomplete="off">

            @error('name')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <label for="staticEmail" class="col-sm-4 col-form-label">Teléfono</label>
          <div class="col-sm-8">
            <input type="number" wire:model.lazy="phone" class="form-control @if($phone == '') @else @error('phone') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Teléfono" autocomplete="off">
            @error('phone')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <label for="staticEmail" class="col-sm-4 col-form-label">Email</label>
          <div class="col-sm-8">
            <input type="email" wire:model.lazy="email" class="form-control @if($email == '') @else @error('email') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="email@example.com" autocomplete="off">
            @error('email')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="form-group row">
          <label for="staticEmail" class="col-sm-4 col-form-label">Dirección</label>
          <div class="col-sm-8">
            <input type="email" wire:model.lazy="address" class="form-control @if($address == '') @else @error('address') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Dirección" autocomplete="off">
            @error('address')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <button type="button" class="btn btn-outline-success float-right ml-2" wire:click="storeOrupdate">Guardar</button>
        <x-adminlte-button class="float-right" wire:click="cancel" theme="danger" label="Cancelar" data-dismiss="modal" />

        <x-slot name="footerSlot" class="mt-0 mb-0 p-0">
          <small id="emailHelp" class="form-text text-muted">* Campo obligatorio</small>
        </x-slot>


      </div>
    </div>

  </div>
</div>

<script>

 window.addEventListener('alert', event => {

        Swal.fire(
            'Proveedor actualizado correctamente',
            '',
            'success'
        )
    })


</script>
        <script>
            window.addEventListener('close-modal', event => {
                     //alert('Hola mundo');
                        $('#providermodal').hide();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();

                    })
        </script>
