<div>
@include('popper::assets')
<div id="alerts" > @include('includes.alert')</div>
    <div class="card">
        <div class="card-header">

            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                @if ($empresa->modal_aut_produc == 1)

                                @endif
                                <i class="bi bi-search mr-3 mt-2" style="cursor: pointer" data-toggle="modal" data-target="#searchproduct"></i>
                            </div>
                            <input type="text" class="form-control @if($error_search) is-invalid @endif" id="inlineFormInputGroup"
                                placeholder="Buscar producto por código" wire:model.lazy ="codigo_de_producto" wire:keydown.enter="searchProductCode" autofocus autocomplete="disabled"> <!--Buscador por código -->
                    </div>
                    @error('codigo_de_producto')
                                <span class="text-danger">{{ $message }}</span>
                    @enderror

            </div>
                </div>
                <div class="row">
                    <div class="col ml-4 mt-1">
                        <Label>Factura electrónica</Label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" disabled>
                            <label class="form-check-label" for="inlineRadio1">Si</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="0" disabled checked>
                            <label class="form-check-label" for="inlineRadio2">No</label>
                          </div>

                    </div>
                   <div class="col">

                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-3 col-form-label">Proceso</label>
                        <div class="col-sm-9">
                            <select class="form-select" aria-label="Default select example" disabled>
                                <option selected>Venta</option>
                                <option value="Venta">Venta</option>
                                <option value="Credito">Venta credito</option>
                                <option value="Consumo Interno">Consumo interno</option>
                              </select>
                        </div>
                      </div>
                    </div>
                    <div class="col">

                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cliente"
                                aria-label="Recipient's username with two button addons"
                                aria-describedby="button-addon4" value="{{ $client_name }}" disabled readonly>
                            <div class="input-group-append" id="button-addon4">
                                <button class="btn btn-outline-secondary" type="button"data-toggle="modal"
                                        data-target="#searchclient"><i class="bi bi-search"
                                        style="cursor: pointer" ></i></button>
                                <button class="btn btn-outline-secondary" type="button"data-toggle="modal" data-target="#clientmodal"><i
                                        class="bi bi-plus-circle-fill" style="cursor: pointer" ></i></button>
                            </div>
                        </div>


                    </div>
                </div>
            </div>



        </div>
    </div>

    <div class="card" style="height: 400px">
        <div class="card-body">
            <div class="table-responsive-md">
                <table class="table" id="tablaProductos" wire:ignore>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Código</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Forma</th>
                            <th scope="col">Precio Unit.</th>
                            <th scope="col">Cant.</th>
                            <th scope="col">Descuento</th>
                            <th scope="col">Total</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
            </div>

        </div>
        <div class="card-footer">
            <div class="col">
                       {{--  <p><strong>Descuento: $ {{ $total_descuento }}</strong></p> --}}
                        <p><strong>Total venta: $ </strong></p>

                    </div>
            <x-adminlte-button label="" theme="success" icon="fas fa-check" class="float-right ml-3" wire:click="pay" />
            </div>

            @include('modals.sale.forma_cantidad')

<script src="{{ asset('js/ventas/procesoVenta.js') }}"></script>
 <script>
        // Verificar la condición
        @if ($empresa->modal_aut_produc == "1")
            $(document).ready(function() {
                // Abrir el modal
                $('#searchproduct').modal('show');
            });
        @endif
</script>
    </div>

</div>

<style>
    .ocultar-columna-id {
        display: none;
    }
</style>

