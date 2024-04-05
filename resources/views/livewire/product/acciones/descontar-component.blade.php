<div class="modal-dialog" theme="primary">
    <div class="modal-content">
        <div class="modal-header bg-danger">
            <h5 class="modal-title " id="staticBackdropLabel"> <strong>Descontar al inventario</strong> </h5>
            <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancelar" aria-label="Close"></button>
        </div>
        <div class="modal-body ">
            <div>
                @include('includes.alert')
                <div class="form-group">
                    <label for="exampleFormControlInput1">Stock actual</label>
                    <input type="number" class="form-control" id="exampleFormControlInput1" wire:model.lazy="stock_actual" readonly disabled>
                </div>

                <div class="form-group">
                    <label for="exampleFormControlInput1">Cantidad a descontar</label>
                    <input type="number" wire:keydown.enter="descontar" wire:model.lazy="cant_descontar" class="form-control @if($cant_descontar == '') @else @error('cant_descontar') is-invalid @else is-valid @enderror @endif" id="cantidad" name="cantidad" placeholder="Ejemplo: 5, 10, 100" autofocus>

                    @error('cant_descontar')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleFormControlInput1">Nuevo stock</label>
                    <input type="number" class="form-control" id="exampleFormControlInput1" wire:model.lazy="nuevo_stock" readonly disabled>
                </div>

                <button type="button" class="btn btn-outline-success float-right ml-2" wire:click="descontar" data-dismiss="modal">Guardar</button>
                <x-adminlte-button class="float-right" wire:click="cancelar" theme="danger" label="Cancelar" data-dismiss="modal" />

                <x-slot name="footerSlot" class="mt-0 mb-0 p-0">
                </x-slot>



            </div>
        </div>
    </div>
</div>
