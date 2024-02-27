<div class="modal-dialog modal-lg" theme="primary">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de gastos</strong> </h5>
            <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>
        </div>
        @include('popper::assets')
        <div class="modal-body ">
            <div class="row">
                <form class="form-floating col-lg-4">
                    <input type="date"
                        class="form-control @if ($fecha == '') @else @error('fecha') is-invalid @else is-valid @enderror @endif"
                        id="floatingInputInvalid" placeholder="name@example.com" wire:model="fecha">
                    <label class="ml-2" for="floatingInputInvalid">Fecha</label>
                    @error('fecha')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </form>

                <form class="form-floating col-lg-4">
                    <input type="text"
                        class="form-control @if ($descripcion == '') @else @error('descripcion') is-invalid @else is-valid @enderror @endif"
                        id="floatingInputInvalid" placeholder="name@example.com" wire:model="descripcion">
                    <label class="ml-2" for="floatingInputInvalid">Descripción</label>
                    @error('descripcion')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </form>


                <form class="form-floating col-lg-4">
                    <input type="number"
                        class="form-control @if ($total == '') @else @error('total') is-invalid @else is-valid @enderror @endif"
                        id="floatingInputInvalid" placeholder="name@example.com" readonly wire:model="total">
                    <label for="floatingInputInvalid" class="ml-2">Total</label>
                    @error('total')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </form>

                <div class="form-floating mt-2 col-lg-4">
                    <select
                        class="form-select @if ($category_gastos_id == '') @else @error('category_gastos_id') is-invalid @else is-valid @enderror @endif"
                        id="floatingSelect" aria-label="Floating label select example" wire:model="category_gastos_id">
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

                <div class="form-floating mt-2 col-lg-4">
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

                <div class="col-lg-4 mt-3">
                    <input type="file"
                        class="form-control @if ($picture == '') @else @error('picture') is-invalid @else is-valid @enderror @endif"
                        id="floatingInputInvalid" placeholder="name@example.com" wire:model="picture">
                    <label class="ml-2" for="floatingInputInvalid"></label>
                    @error('picture')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>
                <a class="btn btn-outline-success mt-2" title="Crear gasto" wire:click="storeOrupdate" ><i class="bi bi-check-circle"></i></a>

                <div class="text-center">
                    Detalles De Gasto
                    <hr>
                </div>
                <form class="form-floating col-lg-5">
                    <input type="text"
                        class="form-control @if ($descripcion_detalles == '') @else @error('descripcion_detalles') is-invalid @else is-valid @enderror @endif"
                        id="floatingInputInvalid" placeholder="name@example.com" wire:model="descripcion_detalles">
                    <label class="ml-2" for="floatingInputInvalid">Descripción</label>
                    @error('descripcion_detalles')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </form>

                <form class="form-floating col-lg-2">
                    <input type="number"
                        class="form-control @if ($cantidad_detalles == '') @else @error('cantidad_detalles') is-invalid @else is-valid @enderror @endif"
                        id="floatingInputInvalid" placeholder="name@example.com" wire:model="cantidad_detalles">
                    <label for="floatingInputInvalid" class="ml-2">Cantidad</label>
                    @error('cantidad_detalles')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </form>

                <form class="form-floating col-lg-2">
                    <input type="number"
                        class="form-control @if ($precio_detalles == '') @else @error('precio_detalles') is-invalid @else is-valid @enderror @endif"
                        id="floatingInputInvalid" placeholder="name@example.com" wire:model="precio_detalles">
                    <label for="floatingInputInvalid" class="ml-2">Precio</label>
                    @error('precio_detalles')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </form>

                <form class="form-floating col-lg-2">
                    <input type="number"
                        class="form-control @if ($subtotal == '') @else @error('subtotal') is-invalid @else is-valid @enderror @endif"
                        id="floatingInputInvalid" placeholder="name@example.com" wire:model="subtotal">
                    <label for="floatingInputInvalid" class="ml-2">SubTotal</label>
                    @error('subtotal')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </form>

                <div class="col-lg-1">
                    <a class="btn btn-outline-success mt-2" title="Agregar" wire:click="añadirddetalle"><i
                            class="bi bi-check-circle"></i></a>
                </div>


            </div>

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
