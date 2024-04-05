<div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" wire:model="name" autocomplete="off">
        <label for="floatingInput">Nombre Impresora</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" id="floatingPassword" placeholder="Password" wire:model="name_pc" autocomplete="off">
        <label for="floatingPassword">Nombre Pc</label>
      </div>

      <div class="form-group row">
        <label for="staticEmail" class="col-sm-12 col-form-label">Predeterminada</label>
        <div class="col-sm-8">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-1" type="radio" name="active_si" id="active_si" value="1" wire:model.lazy = "predeterminada">
                <label class="form-check-label" for="inlineRadio1">Si</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input mt-1" type="radio" name="active_no" id="active_no" value="0" wire:model.lazy = "predeterminada">
                <label class="form-check-label" for="inlineRadio2">No</label>
              </div>
            @error('active')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

      <div class="div mt-2">
        <button type="button" class="btn btn-outline-success float-right mr-2" wire:click="storeOrupdate">Guardar</button>
        <x-adminlte-button class="float-right" data-dismiss="modal" theme="danger" label="Cancelar" />

    </div>

</div>
<script>
    window.addEventListener('close-modal', event => {
             //alert('Hola mundo');
                $('#editarImpresoraModal').hide();
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            })
</script>
