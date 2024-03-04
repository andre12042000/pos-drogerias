<div class="modal-dialog" theme="primary">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de gastos</strong> </h5>
            <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>
        </div>
        @include('popper::assets')
        <div class="modal-body ">

            <form>
                <div class="mb-3">
                    <div class="form-floating mb-3">
                        <input type="date"
                            class="form-control @if ($fecha == '') @else @error('fecha') is-invalid @else is-valid @enderror @endif"
                            id="floatingInput" placeholder="name@example.com" wire:model.defer="fecha">
                        <label for="floatingInput">Fecha de compra *</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating mb-3">
                        <select
                            class="form-select @if ($category_gastos_id == '') @else @error('category_gastos_id') is-invalid @else is-valid @enderror @endif"
                            id="floatingSelect" aria-label="Floating label select example"
                            wire:model="category_gastos_id">
                            <option selected>Selecciona una categoria</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                            @endforeach

                        </select>
                        <label for="floatingSelect">Categoria</label>
                        @error('category_gastos_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating mb-3">
                        <select
                            class="form-select @if ($metodo_pago_id == '') @else @error('metodo_pago_id') is-invalid @else is-valid @enderror @endif"
                            id="floatingSelect" aria-label="Floating label select example" wire:model="metodo_pago_id">
                            <option selected>Selecciona un metodo</option>
                            @foreach ($medios as $medio)
                                <option value="{{ $medio->id }}">{{ $medio->name }}</option>
                            @endforeach
                        </select>
                        <label for="floatingSelect">Metodo De Pago</label>
                        @error('metodo_pago_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-floating">
                        <textarea
                            class="form-control @if ($descripcion == '') @else @error('descripcion') is-invalid @else is-valid @enderror @endif"
                            placeholder="Observación o detalles de la compra" id="floatingTextarea2" style="height: 100px"
                            wire:model.defer = 'descripcion'></textarea>
                        <label for="floatingTextarea2">Observación / Descripción</label>
                    </div>
                    @error('descripcion')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <input type="file"
                        class="form-control @if ($picture == '') @else @error('picture') is-invalid @else is-valid @enderror @endif"
                        id="floatingInputInvalid" placeholder="name@example.com" wire:model="picture">
                    <label class="ml-2" for="floatingInputInvalid"></label>
                    @error('picture')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>

                    @if($photo)
                        <div class="mb-3">
                            <img style="height: 150px; width: 150px;" src="{!! Config::get('app.URL') !!}/storage/{{ $photo }}" alt="">
                        </div>

                    @elseif($picture)
                        <div class="mb-3">
                            <img style="height: 150px; width: 150px;" src="{{ $picture->temporaryUrl() }}" alt="">
                        </div>

                    @endif

                <div class="mb-3 float-right">

                        <button class="btn btn-primary btn-lg" wire:click="save"> Guardar gasto
                            <div wire:loading wire:target="save">
                                <img src="{{ asset('img/loading.gif') }}" width="20px" class="img-fluid"
                                    alt="">
                            </div>
                        </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    window.addEventListener('close-modal', event => {
        //alert('Hola mundo');
        $('#GastosModal').hide();
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    })
</script>
