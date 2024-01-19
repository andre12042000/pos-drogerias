<div class="modal" @if ($showadddiscount) style="display:block" @endif>
    <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
        <div class="modal-content">
            <form wire:submit.prevent="addiscount">

                    <div class="modal-header bg-success">
                        Agregar descuento

                        <button class="btn-close" data-dismiss="modal"></button>

                    </div>

                    <div class="modal-body">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="porcentaje" id="porcentaje"
                                value="porcentaje" wire:model="tipo_porcentaje">
                            <label class="form-check-label" for="inlineRadio1">Porcentaje</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="absoluto" id="absoluto" value="absoluto"
                                wire:model="tipo_porcentaje">
                            <label class="form-check-label" for="inlineRadio2">Absoluto</label>
                        </div>

                        <div class="mb-3 mt-3">
                            <input type="number" class="form-control" id="valuedescuento" placeholder="Porcentaje o valor absoluto" wire:model.lazy = "valuedescuento"  wire:keydown.enter="calcularDescuento" autocomplete="off">
                            <div id="emailHelp" class="form-text">Porcentaje de descuento</div>
                        </div>

                        <div class="mb-3 mt-3">
                            <input type="number" class="form-control" id="exampleFormControlInput1" placeholder="Valor a descontar" value="{{ $descontado }}" readonly>
                            <div id="emailHelp" class="form-text">Valor neto a descontar</div>
                        </div>
                        <div class="mb-3">
                            <input type="number" class="form-control" id="exampleFormControlInput1" placeholder="Total" value="{{ $precio_producto }}" readonly>
                            <div id="emailHelp" class="form-text">Precio total</div>
                        </div>

                    </div>


                    <div class="modal-footer">
                        <button class="btn btn-primary" wire:click="applyDiscount" type="button"
                            data-dismiss="modal">Aplicar
                            <div wire:loading wire:target="savediscount">
                                <img src="{!! Config::get('app.URL') !!}/assets/img/loading.gif" width="20px"
                                    class="img-fluid" alt="">
                            </div>
                        </button>
                    </div>

            </form>
        </div>
    </div>
</div>
