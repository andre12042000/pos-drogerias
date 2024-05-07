<div>
    @include('popper::assets')
    <div> @include('includes.alert')</div>
    <div class="card card-info  ">
        <div class="card-header">





                    <div class="input-group  float-right">
                        <select wire:model="cantidad_registros" class="form-select col-sm-2 mr-2"
                            aria-label="Default select example">
                            <option value="15"> 15 </option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>

                        <select wire:model="filter_asignado" class="form-select col-sm-2 mr-2"
                        aria-label="Default select example">
                        <option value="">Seleccione Asigando</option>
                        <option value="null"> Sin asignado </option>
                        @foreach ($asignados as $asig)
                        <option value="{{ $asig->assigned }}"> {{ $asig->asignado->name }} </option>
                        @endforeach
                        </select>

                      {{--   <select wire:model="filter_proveedor" class="form-select col-sm-4 mr-2"
                            aria-label="Default select example">
                            <option value="">Seleccione Cliente </option>
                            @foreach ($providers as $provider)
                            <option value="{{ $provider->id }}"> {{ $provider->name }} </option>
                            @endforeach
                        </select> --}}
                        <select wire:model="filter_estado" class="form-select col-sm-2 mr-2"
                            aria-label="Default select example">
                            <option value="">Seleccione Estado </option>
                            <option value="1"> En Proceso </option>
                            <option value="2"> Entregado </option>
                        </select>
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar orden" aria-label="Username"
                            aria-describedby="basic-addon1" wire:model="buscar">
                        <button type="button" class="btn btn-outline-light float-right ml-2"  data-toggle="modal" data-target="#ordenModal">Nueva
                            orden <i class="las la-plus-circle"></i></button>
                    </div>


        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Asignado</th>
                        <th>Descripción</th>
                        <th>Cliente</th>
                        <th class="text-end">Valor</th>
                        <th class="text-end">Abono</th>
                        <th class="text-end">Saldo</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</td>
                        <td>@isset($order->asignado->name)
                            {{ $order->asignado->name }}
                            @else
                           <span class="text-secondary">Sin asignar</span>
                        @endisset</td>
                        <td>{{ ucwords($order->fragmento) }} <i @popper({{ $order->descripcion }}) class="bi
                                bi-question-circle float-right" style="cursor:pointer" ></i></td>
                        <td>{{ ucwords($order->client->name) }}</td>

                        <td class="text-end">$ {{ number_format( $order->valor, 0) }}</td>
                        <td class="text-end">$ {{ number_format( $order->abono, 0) }}</td>
                        <td class="text-end">
                            @if($order->saldo > 0) <span class="badge badge-pill badge-danger">
                            <strong> $ {{ number_format( $order->saldo, 0) }}</strong></span>
                            @else <span class="badge badge-pill badge-success">
                                $ {{ number_format( $order->saldo, 0) }}</span>
                            @endif
                        </td>

                        <td>
                            @if($order->status == 1) <span class="badge badge-pill badge-warning">
                            En proceso</span>
                            @else <span class="badge badge-pill badge-success">
                                Entregado</span>
                            @endif
                        </td>
                        <td class="text-center">

                            <a @popper(Abonar) class="btn btn-outline-info btn-sm @if($order->status == 2) disabled @endif" href="#" role="button"
                                data-toggle="modal" data-target="#abonomodal"
                                wire:click="sendDataAbono( {{ $order }} )" ><i class="bi bi-currency-dollar"></i></a>


                            <a @popper(Editar) class="btn btn-outline-success btn-sm @if($order->status == 2) disabled @endif" href="{{ route('orders.edit', $order) }} " role="button"><i class="bi bi-pencil-square"></i></a>
                            <a @popper(Detalles) class="btn btn-outline-primary btn-sm" href="{{ route('orders.show', $order->id) }} " role="button"><i class="bi bi-eye-fill"></i></a>

                            <button @popper(Eliminar) class="btn btn-outline-danger btn-sm @if($order->status == 2) disabled @endif"
                                wire:click="destroy( {{ $order->id }} )"><i class="bi bi-trash3"></i></button>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="10">
                            <p>No se encontraron registros...</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->count() >= $cantidad_registros)
        <div class="card-footer">
            <nav aria-label="...">
                <ul class="pagination">
                    {{ $orders->links() }}
                </ul>
            </nav>
        </div>
        @endif
    </div>
    @push('js')

    <script>
        function redirigir() {
            location.replace('{!! Config::get('app.URL') !!}/orders/create');
        }
    </script>

    @endpush
</div>
