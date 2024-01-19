<div class="modal-dialog" theme="primary">
  <div class="modal-content">
    <div class="modal-header bg-primary">
      <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de cliente</strong> </h5>
      <button type="button" class="btn-close " data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>
    </div>
    @include('popper::assets')
    <div class="modal-body ">

      <div>
        @include('includes.alert')

        <form wire:submit.prevent="storeOrupdate">
          <div class="form-group row">
            <label for="staticEmail" class="col-sm-4 col-form-label">Tipo documento</label>
            <div class="col-sm-8">
              <select id="inputState" class="form-control @if($type_document == '') @else @error('type_document') is-invalid @else is-valid @enderror @endif" wire:model.lazy="type_document" autofocus>
                <option selected value="">Seleccionar tipo de documento...</option>
                <option value="CC">Cedula de ciudadanía</option>
                <option value="CE">Cedula de extranjeria</option>
                <option value="NIT">Nit persona jurídica</option>
                <option value="PE">Pasaporte</option>
                <option value="TDE">Tipo de documento extranjero</option>
                <option value="NN">Sin identificación</option>
                <option value="OTRO">Otro</option>
              </select>
              @error('type_document')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-group row">
            <label for="number_document" class="col-sm-4 col-form-label">Nro documento</label>
            <div class="col-sm-8">
              <input type="number" wire:model.lazy="number_document" id="documento" name="documento" class="form-control @if($number_document == '') @else @error('number_document') is-invalid @else is-valid @enderror @endif" id="number_document" placeholder="Numero de documento" autocomplete="off">
              @error('number_document')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-group row">
            <label for="name" class="col-sm-4 col-form-label">Nombres<span class="fs-6 text-danger ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
            <div class="col-sm-8">
              <input type="text" wire:model.lazy="name" class="form-control @if($name == '') @else @error('name') is-invalid @else is-valid @enderror @endif" id="name" placeholder="Ejemplo: Juan García" autocomplete="off">

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
            <label for="direccion" class="col-sm-4 col-form-label">Dirección</label>
            <div class="col-sm-8">
              <input type="text" wire:model.lazy="address" class="form-control @if($address == '') @else @error('address') is-invalid @else is-valid @enderror @endif" id="direccion" placeholder="Dirección" autocomplete="off">
              @error('address')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <button type="submit" class="btn btn-success float-right ml-2" >Guardar</button>
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
            'Cliente actualizado correctamente',
            '',
            'success'
        )
    })


</script>

        <script>
            window.addEventListener('close-modal', event => {
                     //alert('Hola mundo');
                        $('#clientmodal').hide();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();

                    })
        </script>


