<div>
    <div>
        @include('popper::assets')
        <div class="form-row">
            <div class="form-group col-lg-6">
                <label for="exampleFormControlInput1">Nombre de la empresa</label>
                <input type="text"  wire:model.lazy="name" id="name" name="name" class="form-control @if($name == '') @else @error('name') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Nombre">

                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-4">
                <label for="exampleFormControlInput1">Ingresa el NIT</label>
                <input type="number"  wire:model.lazy="nit" class="form-control @if($nit == '') @else @error('nit') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Nit de la empresa">

                @error('nit')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-2">
                <label for="exampleFormControlInput1">DV</label>
                <input type="number"  wire:model.lazy="dv" class="form-control @if($dv == '') @else @error('dv') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Dígito de verificación">

                @error('dv')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-lg-6">
                <label for="exampleFormControlInput1">Ingresa el telefono</label>
                <input type="number"  wire:model.lazy="telefono" class="form-control @if($telefono == '') @else @error('telefono') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Telefono de la empresa">

                @error('telefono')
                <span class="text-danger">{{ $message }}</span>
                @enderror

            </div>
            <div class="form-group col-lg-6">
                <label for="exampleFormControlInput1">Correo electrónico</label>
                <input type="text"  wire:model.lazy="email" class="form-control @if($email == '') @else @error('email') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Correo electrónico">

                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-lg-6">
                <label for="exampleFormControlInput1">Prefijo facturación</label>
                <input type="text"  wire:model.lazy="pre_factu" class="form-control @if($pre_factu == '') @else @error('pre_factu') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Prefijo de facturación">

                @error('pre_factu')
                <span class="text-danger">{{ $message }}</span>
                @enderror

            </div>
            <div class="form-group col-lg-6">
                <label for="exampleFormControlInput1">Prefijo de servicio</label>
                <input type="text"  wire:model.lazy="pre_servi" class="form-control @if($pre_servi == '') @else @error('pre_servi') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Prefijo de servicio">

                @error('pre_servi')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-lg-4">
                <label for="exampleFormControlInput1">Prefijo orden</label>
                <input type="text"  wire:model.lazy="pre_orden" class="form-control @if($pre_orden == '') @else @error('pre_orden') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Prefijo de facturación">

                @error('pre_orden')
                <span class="text-danger">{{ $message }}</span>
                @enderror

            </div>
            <div class="form-group col-lg-4">
                <label for="exampleFormControlInput1">Prefijo abono</label>
                <input type="text"  wire:model.lazy="pre_abono" class="form-control @if($pre_abono == '') @else @error('pre_abono') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Prefijo de abonos">

                @error('pre_abono')
                <span class="text-danger">{{ $message }}</span>
                @enderror

            </div>




            <div class="mb-3 col-lg-4">
                <label for="exampleInputPassword1" class="form-label">¿Modal productos automatico?</label>
                <div class=" mt-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="activos_si" id="activos_si" value="1" wire:model.lazy="modalaut">
                        <label class="form-check-label" for="inlineRadio1">Si</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="activos_no" id="activos_no" value="0" wire:model.lazy="modalaut">
                        <label class="form-check-label" for="inlineRadio2">No</label>
                    </div>

                </div>
                @error('activo')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>





        </div>
        <div class="form-row">
            <div class="form-group col-lg-6">
                <label for="inputEmail4">Logo</label>
                <input type="file" class="form-control @if($image == '') @else @error('image') is-invalid @else is-valid @enderror @endif" id="inputEmail4" wire:model="image">
                @error('image')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label for="exampleFormControlInput1">Dirección</label>
                <input type="text"  wire:model.lazy="direccion" class="form-control @if($direccion == '') @else @error('direccion') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Ingresa tu dirección">

                @error('direccion')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-12">
            <br>
                <div class="div mt-2">
                    <button type="button" class="btn btn-outline-success float-right mr-2" wire:click="update">Guardar</button>
                    <x-adminlte-button class="float-right" data-dismiss="modal" theme="danger" label="Cancelar" />

                </div>

            </div>
        </div>



    </div>
    @if($photo)
    <div class="mb-4"> <img style="height: 150px; width: 150px;" src="{!! Config::get('app.URL') !!}/storage/{{ $photo }}" alt="">
    </div>

    @elseif($image)
    <div class="mb-4"> <img style="height: 150px; width: 150px;" src="{{ $image->temporaryUrl() }}" alt="">
    </div>

    @endif





</div>

