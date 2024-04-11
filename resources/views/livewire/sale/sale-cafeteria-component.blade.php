<div>
    @section('title', 'Venta Por Mesas')

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-7">

                <div class="card" id="cuentas_mostrador" style="height: 550px;">

                    <div class="row m-2">
                        <div class="col-sm-3">
                            <h5><span class="badge bg-warning text-dark">MOSTRADOR</span></h5>
                        </div>
                        <div class="col-sm-7">

                            <div class="col">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <i class="bi bi-search mr-3 mt-2" style="cursor: pointer" data-toggle="modal"
                                            data-target="#searchproductrestaurant"></i>
                                    </div>
                                    <input type="text"
                                        class="form-control @if ($error_search) is-invalid @endif"
                                        id="inlineFormInputGroup" placeholder="Buscar producto por código"
                                        wire:model.lazy ="codigo_de_producto" wire:keydown.enter="searchProductCode"
                                        autofocus autocomplete="nope"> <!--Buscador por código -->
                                </div>
                                @error('codigo_de_producto')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-2">
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
        height: 180px;
        background-color: #FFC107;
        border-radius: 15px;
        text-align: center;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Sombra suave para resaltar */
        transition: box-shadow 0.3s ease;
        /* Transición suave al pasar el cursor */
    }

    .counter:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        /* Aumentar la sombra al pasar el cursor */
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
        width: 250px;
        height: 250px;
        background-color: #A1C398;
        border: 1px solid #000000;
        border-radius: 50%;
        margin-bottom: 10px;
    }

    .mesa-box {
        position: relative;
        width: 150px;
        height: 80px;
        background-color: #A1C398;
        border: 1px solid #000000;
        border-radius: 15px;
        margin: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        text-align: center;
        margin-bottom: 10px;
    }

    .select2-selection--single {
        height: 40px !important;
    }

    .select2-selection__arrow {
        top: 10px !important;
        right: 10px !important;
        /* Ajusta la posición vertical del ícono */
    }



    .etiqueta {
        display: block;
        background-color: transparent;
        padding: 5px;
        font-size: 10px;
        position: absolute;
        bottom: 2px;
        /* Alinea la etiqueta en la parte inferior */
        left: 50%;
        /* Ajusta la posición horizontal de la etiqueta al centro */
        transform: translateX(-50%);
        /* Centra la etiqueta horizontalmente */
        color: white;
        width: 100px;
        /* Ancho de la etiqueta, ajusta según sea necesario */
    }
</style>


<script src="{{ asset('js/ventas/procesoVentaModoRestaurant.js') }}"></script>
