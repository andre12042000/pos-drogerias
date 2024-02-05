<div>
    <div class="modal-dialog modal-m" theme="primary">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de
                        sitios para control de temperatura</strong> </h5>
                <button type="button" class="btn-close" data-dismiss="modal" wire:click="cleanData"
                    aria-label="Close"></button>
            </div>
            @include('popper::assets')
            <div class="modal-body">
                <div>@include('includes.alert')</div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nombre" placeholder="name@example.com" wire:model.lazy = 'name'>
                    <label for="nombre">Nombre / Sitio</label>

                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="ACTIVE" wire:model.defer = 'status' checked>
                    <label class="form-check-label" for="inlineRadio1">Activo</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="DESACTIVE" wire.model.defer = 'status'>
                    <label class="form-check-label" for="inlineRadio2">Inactivo</label>
                  </div>
            <hr>


            <button type="button" class="btn btn-success float-right ml-2"
                            wire:click="save">Guardar</button>
            <x-adminlte-button class="float-right" wire:click="cleanData" theme="danger" label="Cancelar"
                            data-dismiss="modal" />
            </div>
        </div>
    </div>



</div>
