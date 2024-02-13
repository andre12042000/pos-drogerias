<div class="modal-dialog" theme="primary">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title " id="staticBackdropLabel"> <strong>Registrar factura de compra</strong> </h5>
            <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>

        </div>
        @include('popper::assets')
        <div class="modal-body ">
            <div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-4 col-form-label">Fecha <span class="fs-6 text-danger ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                    <div class="col-sm-8">
                        <input type="date" wire:model.lazy="fecha" id="fecha" name="fecha" class="form-control @if($fecha == '') @else @error('fecha') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1">
                        @error('fecha')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-4 col-form-label">Proveedor <span class="fs-6 text-danger ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                    <div class="col-sm-8">
                        <select id="inputState" wire:model.lazy="proveedor" class="form-control @if($proveedor == '') @else @error('proveedor') is-invalid @else is-valid @enderror @endif">
                            <option value="">Seleccionar proveedor</option>
                            @foreach ($providers as $provider)
                            <option value="{{ $provider->id}}">{{ mb_strtoupper($provider->name) }}</option>
                            @endforeach
                        </select>
                        @error('proveedor')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-4 col-form-label">Factura Nro. <span class="fs-6 text-danger ml-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" wire:model.lazy="factura_nro" class="form-control @if($factura_nro == '') @else @error('factura_nro') is-invalid @else is-valid @enderror @endif"  placeholder="Numero de documento">
                        @error('factura_nro')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- <div class="form-group row">
        <label for="staticEmail" class="col-sm-4 col-form-label">% de Iva </label>
        <div class="col-sm-8">
            <input type="number" wire:model.lazy="porcentaje" class="form-control @if($porcentaje == '') @else @error('factura_nro') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Numero de documento" >
            @error('porcentaje')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
      </div> -->

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Total de factura </label>
                    <div class="col-sm-8">
                        <input type="number" wire:model.lazy="total_factura" class="form-control @if($total_factura == '') @else @error('total_factura') is-invalid @else is-valid @enderror @endif" placeholder="Numero de documento" disabled>
                        @error('total_factura')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label  class="col-sm-4 col-form-label">Forma De Pago</label>
                    <div class="col-sm-8">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="form_pago_si" id="form_pago_si" value="2" wire:model.lazy="form_pago">
                            <label class="form-check-label" for="inlineRadio1">Contado</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="form_pago_no" id="form_pago_no" value="1" wire:model.lazy="form_pago">
                            <label class="form-check-label" for="inlineRadio2">Credito</label>
                        </div>
                        @error('form_pago')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror <br>
                        @if($form_pago == 1)
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Próximo Pago</label>
                            <div class="col-sm-8">
                                <input type="date" wire:model.lazy="proximo_pago" class="form-control mt-3 @if($proximo_pago == '') @else @error('proximo_pago') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1">
                                @error('proximo_pago')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Abono </label>
                            <div class="col-sm-8">
                                <input type="number" wire:model.lazy="abono_compra" class="form-control @if($abono_compra == '') @else @error('abono_compra') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Abono">
                                @error('abono_compra')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-4 col-form-label">Comprobante </label>
                    <div class="col-sm-8">
                        <input type="file" wire:model.lazy="picture" class="form-control" >
                        @error('picture')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>



                @if($photo)
                <div class="mb-4"> <img style="height: 150px; width: 150px;" src="{!! Config::get('app.URL') !!}/storage/{{ $photo }}" alt="">
                </div>

                @elseif($picture)
                <div class="mb-4"> <img style="height: 150px; width: 150px;" src="{{ $picture->temporaryUrl() }}" alt="">
                </div>

                @endif

                <button type="button" class="btn btn-success float-right ml-2" wire:click="save">Guardar</button>
                <x-adminlte-button class="float-right" wire:click="cancel" theme="danger" label="Cancelar" data-dismiss="modal" />

                <x-slot name="footerSlot" class="mt-0 mb-0 p-0">
                    <small id="emailHelp" class="form-text text-muted">* Campo obligatorio</small>
                </x-slot>
            </div>
        </div>

    </div>
</div>
