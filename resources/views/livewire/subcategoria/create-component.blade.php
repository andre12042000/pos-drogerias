<div class="modal-dialog" theme="primary">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de laboratorio</strong> </h5>
            <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>
        </div>
        @include('popper::assets')
        <div class="modal-body ">

            <div>
                @include('includes.alert')
                <div class="form-group">
                    <label for="exampleFormControlInput1">Nombre de subcategoria</label>
                    <input type="text" wire:keydown.enter="storeOrupdate" wire:model.lazy="name" id="name" name="name" class="form-control @if($name == '') @else @error('name') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Ejemplo: paletas dulces" autocomplete="off" autofocus>
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-row mb-0" x-data="{
                    open: false,
                    get isOpen() { return this.open },
                    toggle() { this.open = !this.open },
                 }">
                <div class="form-group col-md-12">
                    <label for="inputEmail4">Categoría <i @popper(Crear categoría) class="bi bi-plus-circle-fill ml-1 text-success" style="cursor: pointer" @click="toggle()"></i> </label>
                    <div x-show="isOpen">
                      <input type="text" class="form-control @if($nuevacategoria == '') @else @error('nuevacategoria') is-invalid @else is-valid @enderror @endif mb-2" placeholder="Nueva categoría" wire:keydown.enter="guardarCategoria" wire:model.lazy="nuevacategoria" autocomplete="off">
                      @error('nuevacategoria')
                      <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>
                    <select class="form-select @if($categoria_id == '') @else @error('categoria_id') is-invalid @else is-valid @enderror @endif" id="exampleFormControlSelect1" wire:model.lazy="categoria_id">
                      <option value="">Seleccione una categoría</option>
                      @foreach ($categories as $category)
                      <option value="{{ $category->id }}">{{ ucwords($category->name) }}</option>
                      @endforeach
                    </select>
                    @error('categoria_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
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
                        $('#subModal').hide();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    })
        </script>
