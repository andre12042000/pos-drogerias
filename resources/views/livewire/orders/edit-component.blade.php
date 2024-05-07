<div>
    @include('popper::assets')
    @section('title', 'Orden De Trabajo')

<div class="py-2">
    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary float-end"><i class="bi bi-arrow-left-circle"></i> Atrás</a>

</div>
    <div class="card-body mt-4">
        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    @if ($status == 1)
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon bg-success">
                                ABIERTO
                            </div>
                        </div>
                    @else
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon bg-dark">
                                CERRADO
                            </div>
                        </div>
                    @endif

                    <div class="card-header">
                        <h4 class="text-center"><strong>Información Básica</strong></h4>
                    </div>
                    <div class="card-body">


                        <ul class="mt-5">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label" for="nombre"><strong>Orden Nro.</strong></label>
                                <p class="text-bold">{{ $full_numero }}</p>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label" for="nombre"><strong>Cliente</strong></label>
                                <p>{{ ucwords($client_name) }}</p>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label" for="num_compra"><strong>Fecha</strong></label>
                                <p> {{ \Carbon\Carbon::parse($fecha)->format('d M Y') }} </p>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label" for="num_compra"><strong>Asignado a</strong></label>
                                <p>
                                    {{ $asignado }} <button class="btn btn-outlife-success"
                                        wire:click="sendOrden( {{ $order }} )"><i
                                            class="bi bi-plus-circle text-success " style="cursor: pointer"
                                            data-toggle="modal" data-target="#asignadomodal"
                                            title="Actualizar asignación"></i></button>
                                </p>
                            </li>


                            @if (!empty($descripcion))
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label mr-3" for="num_compra"><strong>Descripción</strong>
                                        <br></label>
                                    <p>{{ $descripcion }}</p>
                                </li>
                            @endif

                        </ul>

                        <hr>
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne" aria-expanded="false"
                                        aria-controls="flush-collapseOne">
                                        Historial de asignación
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            @if (count($historiales) < 1)
                                                No hay asignaciones aun!
                                            @else
                                                @foreach ($historiales as $tecnico)
                                                    <ol class="list-group ">
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-start">
                                                            <div class="ms-2 me-auto">
                                                                <div class="fw-bold"><i class="fas fa-user-cog"></i>
                                                                    {{ $tecnico->user->name }}</div>

                                                            </div>
                                                            <span
                                                                class="badge bg-primary rounded-pill">{{ \Carbon\Carbon::parse($tecnico->created_at)->diffForHumans() }}
                                                            </span>
                                                        </li>
                                                    </ol>
                                                @endforeach
                                            @endif

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <ul class="mt-5">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label" for="nombre"><strong>TOTAL</strong></label>
                                <p class="text-bold">$ {{ number_format($valor, 0) }}</p>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label" for="nombre"><strong>ABONADO</strong></label>
                                <p class="text-bold">$ {{ number_format($abono, 0) }}</p>
                            </li>

                            <li
                                class="list-group-item d-flex justify-content-between align-items-center @if ($saldo > 0) list-group-item-danger
                        @else
                            list-group-item-info @endif">
                                <label class="form-control-label" for="nombre"><strong>SALDO</strong></label>
                                <p class="text-bold">$ {{ number_format($saldo, 0) }}</p>
                            </li>
                        </ul>


                        <ul class="mt-5">
                            <div class="d-grid gap-2">
                                <button
                                    class="btn btn-outline-dark float-right @if ($status == 2 || $saldo != 0) disabled @endif"
                                    wire:click = 'cerrar_orden'> Cerrar Orden</button>

                            </div>
                        </ul>



                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center"><strong>Detalles</strong></h4>
                    </div>

                    @if ($details->count() > 0)
                        <div class="card-footer">


                            <table id="detalles" class="table">
                                <thead>
                                    <tr>

                                        <th class="text-center">Producto</th>
                                        <th class="text-right">Precio Unitario</th>
                                        <th class="text-right">Cantidad</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($details as $detalle)
                                        <tr>
                                            <td>{{ $detalle->product->name }}</td>

                                            <td class="text-center">$ {{ number_format($detalle->price, 0) }}</td>
                                            <td class="text-center">{{ $detalle->quantity }}</td>
                                            <td class="text-center">$ {{ $detalle->price * $detalle->quantity }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif


                    @if ($detail_abonos->count() > 0)
                        <div class="card-footer">
                            <div class="card-header">
                                <div class="row">
                                    <p class="text-bold col-10"> Historial de Abonos </p>
                                    <button
                                        class="btn btn-outline-dark float-right col-2 @if ($status == 2) disabled @endif"
                                        data-toggle="modal" data-target="#abonomodal"
                                        wire:click="sendDataAbono({{ $order }})">Abonar <i
                                            class="las la-plus-circle"></i></button>
                                </div>
                            </div>

                            <table id="detalles" class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Código Abono</th>
                                        <th class="text-right">Método de pago</th>
                                        <th class="text-right">Vendedor/a</th>
                                        <th class="text-right">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detail_abonos as $abono)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($abono->created_at)->format('d M
                                                                                                                                                                                        Y') }}
                                            </td>
                                            <td class="text-center">{{ $abono->full_nro }}</td>
                                            <td class="text-center">{{ $abono->metodopago }}</td>
                                            <td class="text-center">{{ ucwords($abono->user->name) }}</td>
                                            <td class="text-right"> $ {{ number_format($abono->amount, 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="card">
                    <div class="card-header text-center">
                        <h4>Añadir productos</h4>
                    </div>

                    <div class="row mt-2">

                        <div class="col ml-2 mr-2">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <i class="bi bi-search mr-3 mt-2" style="cursor: pointer" data-toggle="modal"
                                        data-target="#searchproduct"></i>
                                </div>
                                <input type="text"
                                    class="form-control @if ($error_search) is-invalid @endif"
                                    id="inlineFormInputGroup" placeholder="Buscar producto por código"
                                    wire:model.lazy="codigo_de_producto" wire:keydown.enter="searchProductCode"
                                    autocomplete="disabled">
                                <!--Buscador por código -->
                            </div>
                            @error('codigo_de_producto')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="table-responsive-md mb-2 ml-2">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Código</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Precio Unit.</th>
                                            <th scope="col">Cant.</th>
                                            <th scope="col">Total</th>
                                            <th scope="col" class="text-center">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($this->productssale as $key => $product)
                                            <tr>

                                                <td>{{ $product['code'] }}</td>
                                                <td>{{ $product['name'] }}</td>
                                                <td>$ {{ number_format($product['price'], 0) }}</td>
                                                <td>{{ $product['quantity'] }}</td>
                                                <td>$ {{ number_format($product['total'], 0) }}</td>
                                                <td class="text-end">
                                                    <div class="btn-toolbar float-right" role="toolbar"
                                                        aria-label="Toolbar with button groups">
                                                        <div class="btn-group-sm mr-2" role="group"
                                                            aria-label="First group">
                                                            @include('popper::assets')

                                                            <button type="button" class="btn btn-outline-primary"
                                                                wire:click="modificarpreciounitario({{ $key }}, {{ $product['price'] }}, {{ $product['quantity'] }}, {{ $product['total'] }})"><i
                                                                    class="bi bi-pencil-square"></i></button>
                                                            <!-- Editar -->

                                                            <button type="button" class="btn btn-outline-success"
                                                                wire:click="addQuantity({{ $key }})"><i
                                                                    class="bi bi-plus-lg"></i>
                                                                <!-- Incrementar --></button>

                                                            <button type="button" class="btn btn-outline-secondary"
                                                                wire:click="downCantidad({{ $key }})"><i
                                                                    class="bi bi-dash-lg"></i></button> <!-- Restar -->

                                                            <button @popper(Eliminar) type="button"
                                                                class="btn btn-outline-danger"
                                                                wire:click="destroyItem({{ $key }})"><i
                                                                    class="bi bi-trash3"></i>
                                                            </button> <!-- Eliminar -->
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="8">
                                                    <p>No hay productos agregados para esta orden!!</p>
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                            <button @if (empty($productssale)) disabled @endif
                                class="btn btn-outline-success float-end m-2" wire:click="agregarproduct">
                                <i class="bi bi-check2-circle"></i></button>
                        </div>
                        @include('modals.orders.edit')
                    </div>
                </div>
            </div>
            @livewire('orders.comentario-component', ['order' => $id_order, 'status' => $status])
        </div>
    </div>
</div>
</div>
@include('modals.products.search')
@include('modals.orders.updateasig')


@include('modals.abono.abono')
