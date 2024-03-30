<div>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-7">

                <div class="card" id="cuentas_mostrador" style="height: 550px;">

                    <div class="row m-2">
                        <div class="col">
                            <h5><span class="badge bg-success">MOSTRADOR</span></h5>
                        </div>
                        <div class="col">

                            <div class="col">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <i class="bi bi-search mr-3 mt-2" style="cursor: pointer" data-toggle="modal"
                                            data-target="#searchproductrestaurant"></i>
                                    </div>
                                    <input type="text"
                                        class="form-control @if ($error_search) is-invalid @endif"
                                        id="inlineFormInputGroup" placeholder="Buscar producto por código"
                                        wire:model.lazy ="codigo_de_producto" wire:keydown.enter="searchProductCode" autofocus
                                        autocomplete="disabled"> <!--Buscador por código -->
                                </div>
                                @error('codigo_de_producto')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check float-right">
                                <input class="form-check-input" type="checkbox" value="" id="pagarMostrador">
                                <label class="form-check-label" for="pagarMostrador">
                                  <strong>Pagar</strong>
                                </label>
                              </div>
                        </div>

                    </div>
                    <div class="row m-4">

                        <table class="table" id="productosVentaMostrador">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Precio Unit.</th>
                                <th scope="col">Cant.</th>
                                <th scope="col">Total</th>
                                <th scope="col"></th>
                              </tr>
                            </thead>
                            <tbody id="productosVentaMostrador">

                            </tbody>
                          </table>

                    </div>

                    {{-- Contenido dinámico --}}
                </div>

                <div class="card" id="cuentas_por_mesas" style="height: 550px;">
                    {{-- Contenido dinámico --}}
                </div>

            </div>
            <div class="col-md-5">
                <div class="card" id="componente_pago" style="height: 550px;">
                    @include('livewire.sale.subforms.pagos')
                </div>
            </div>
        </div>
        <div class="row row-expand">
            <div class="col-md-12">
                <div class="card" id="mesas" style="height: 200px;">
                    @include('livewire.sale.subforms.mesas')
                    <span class="position-absolute top-0 end-0 m-2">
                        <i class="fas fa-cog" style="cursor: pointer" data-bs-toggle="modal"
                            data-bs-target="#cantidadMesasModal"></i>
                    </span>

                </div>
            </div>
        </div>
    </div>
    @include('modals.sale.pedidos')
</div>
@include('modals.sale.editarcantidadmesas')
@include('modals.client.search')
@include('modals.client.create')
@include('modals.products.search_product_restaurant')


<style>
    .container-fluid {
        display: flex;
        flex-direction: column;
        height: 100vh;
        /* Altura total de la ventana */
    }

    .row-expand {
        flex-grow: 1;
        /* Hace que este div crezca y ocupe todo el espacio restante */
    }

    .bottom-section {
        display: flex;
        justify-content: space-around;
        align-items: center;
        background-color: #f0f0f0;
        padding: 20px;
    }

    .counter,
    .tables {
        text-align: center;
    }

    .counter {
        width: 200px;
        /* Ancho del mostrador */
        height: 150px;
        /* Altura del mostrador */
        background-color: #e0e0e0;
        /* Color de fondo del mostrador */
        border-radius: 15px;
        /* Radio de los bordes redondeados */
        text-align: center;
        padding: 20px;
    }

    .tables {
        flex: 3;
        /* Ocupa tres veces más espacio que el mostrador */
    }

    .counter h3,
    .tables h3 {
        margin-bottom: 10px;
    }

    /* Estilos para las mesas */
    .tables .table {
        width: 100px;
        height: 100px;
        background-color: #ffffff;
        border: 1px solid #000000;
        border-radius: 50%;
        margin-bottom: 10px;
    }

    .mesa-box {
        width: 100px;
        height: 100px;
        background-color: #ffffff;
        border: 1px solid #000000;
        border-radius: 15px;
        margin: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }

    .select2-selection--single {
            height: 40px !important;
        }

        .select2-selection__arrow {
            top: 10px !important;
            right: 10px !important; /* Ajusta la posición vertical del ícono */
        }
</style>


<script src="{{ asset('js/ventas/procesoVentaModoRestaurant.js') }}"></script>
