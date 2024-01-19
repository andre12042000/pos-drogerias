<div class="modal" @if ($showEdit) style="display:block" @endif data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered md" role="document" >
        <div class="modal-content">
            <form wire:submit.prevent="save">

                    <div class="modal-header bg-primary">
                      <strong> <i class="bi bi-cart4"></i> Editar item</strong>
                      <button type="button" class="btn-close" wire:click="close_edit" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                    <div class="form-group row">
                            <label for="abona" class="col-sm-4 col-form-label" placeholder="Cantidad recibida">Precio unitario</label>
                            <div class="col-sm-8">
                              <input type="number" class="form-control" id="cantidad" wire:model.lazy = 'precio_unitario_item' wire:keydown.enter="updateCantidadRecibida">
                            </div>
                    </div>

                    <div class="form-group row">
                        <label for="abona" class="col-sm-4 col-form-label" placeholder="Cantidad recibida">Cantidad</label>
                        <div class="col-sm-8">
                          <input type="number" class="form-control" id="cantidad" wire:model.lazy = 'cantidad_item' wire:keydown.enter="updateCantidadRecibida">
                        </div>
                    </div>

                <div class="form-group row">
                    <label for="abona" class="col-sm-4 col-form-label" placeholder="Cantidad recibida">Total</label>
                    <div class="col-sm-8">
                      <input type="number" class="form-control" id="cantidad" wire:model.lazy = 'total_item' disabled readonly>
                    </div>
                </div>










                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary" wire:click="update_item" >ACTUALIZAR
                            <div wire:loading wire:target="save">
                                <img src="{!! Config::get('app.URL') !!}/assets/img/loading.gif" width="20px"
                                    class="img-fluid" alt="">
                            </div>
                        </a>

                    </div>

            </form>
        </div>
    </div>
</div>
