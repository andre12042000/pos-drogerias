<div class="modal-dialog" theme="primary">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title " id="staticBackdropLabel"> <strong>Añadir al inventario</strong> </h5>
                        <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancelar"   aria-label="Close"></button>

            </div>
            <div class="modal-body ">

<div>
 @include('includes.alert')

 <div class="form-group">
    <label for="exampleFormControlInput1">Stock actual</label>
    <input type="number" class="form-control" id="exampleFormControlInput1"  wire:model.lazy = "stock_actual" readonly disabled>
</div>

    <div class="form-group">
        <label for="exampleFormControlInput1">Cantidad a agregar</label>
        <input type="text" wire:keydown.enter="agregar" wire:model.lazy="cant_agregar" class="form-control @if($cant_agregar == '') @else @error('cant_agregar') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Ejemplo: 5, 10, 100" autocomplete="off" >

        @error('cant_agregar')
        <span class="text-danger">{{ $message }}</span>
        @enderror


    </div>
    <div class="form-group">
        <label for="exampleFormControlInput1">Nuevo stock</label>
        <input type="number" class="form-control" id="exampleFormControlInput1"  wire:model.lazy = "nuevo_stock" readonly disabled>
    </div>

    <button type="button" class="btn btn-outline-success float-right ml-2" wire:click="agregar" data-dismiss="modal">Guardar</button>
    <x-adminlte-button class="float-right" wire:click="cancelar" theme="danger" label="Cancelar" data-dismiss="modal"/>

    <x-slot name="footerSlot" class="mt-0 mb-0 p-0">
    </x-slot>



</div>
</div>

</div>
</div>
