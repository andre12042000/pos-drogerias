<div>
    @include('includes.alert')
    @include('popper::assets')
    <div class="card card-info mt-1">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-8">
                    <h3>Nueva orden de trabajo</h3>
                </div>
                <div class="col-sm-4">
                        <button class="btn btn-outline-light btn-sm mt-1 float-right" onclick="redirigir()"><i class='bi bi-arrow-left-circle'></i>  Atrás</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">

           {{--      <div class="col">
                    <label for="exampleInputEmail1" class="form-label">Proveedor</label>
                    <div class="input-group">
                        <select class="form-select" aria-label="Default select example" wire:model.lazy = "provider">
                            <option value="">Seleccionar proveedor</option>
                            @foreach ($providers as $provider)
                            <option value="{{ $provider->id }}"> {{ $provider->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
                <div class="col">
                    <label for="exampleInputEmail1" class="form-label">Cliente <span class="fs-6 text-danger ml-2"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Buscar o crear cliente"
                            aria-label="Recipient's username with two button addons" aria-describedby="button-addon4"
                            value="{{ $client_name }}" disabled readonly>
                        <div class="input-group-append" id="button-addon4">
                            <button class="btn btn-outline-secondary" type="button" data-toggle="modal"
                                data-target="#searchclient"><i class="bi bi-search"
                                    style="cursor: pointer"></i></button>
                            <button class="btn btn-outline-secondary" type="button" data-toggle="modal"
                                data-target="#clientmodal"><i class="bi bi-plus-circle-fill"
                                    style="cursor: pointer"></i></button>
                        </div>
                    </div>
                    @error('client_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col">
                    <label for="exampleInputEmail1" class="form-label">Equipo<span class="fs-6 text-danger ml-2"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar o crear equipo"
                                aria-label="Recipient's username with two button addons" aria-describedby="button-addon4"
                                value="{{ $equipo_name }}" disabled readonly>
                            <div class="input-group-append" id="button-addon4">
                                <button class="btn btn-outline-secondary" title="Buscar un equipo" type="button" data-toggle="modal"
                                    data-target="#searchequipo"><i class="bi bi-search"
                                        style="cursor: pointer"></i></button>
                                <button class="btn btn-outline-secondary" title="Crear un equipo" type="button" data-toggle="modal"
                                    data-target="#modalEquipos"><i class="bi bi-plus-circle-fill"
                                        style="cursor: pointer"></i></button>

                                       {{--  <button class="btn btn-outline-secondary" title="Historial de mantenimientos" type="button" data-toggle="modal"
                                    data-target="#searchequipo"><i class="bi bi-list-check"
                                        style="cursor: pointer"></i></button> --}}
                            </div>
                        </div>
                    @error('equipo')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Asignar a</label>
                        <select class="form-select" wire:model='asignar'>
                            <option value="">Seleccione un técnico</option>
                          @foreach ($tecnicos as $tecnico )
                          <option value="{{$tecnico->id}}">{{$tecnico->name}}</option>
                          @endforeach
                        </select>
                      </div>
                </div>
            </div>

            <div class="row mt-2 mb-4">
                <div class="col">
                    <label for="exampleInputEmail1" class="form-label">Descripción</label>
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2"
                            style="height: 80px" wire:model.lazy = "descripcion"></textarea>
                    </div>

                </div>
            </div>
            <label for="exampleInputEmail1" class="form-label">Productos</label>
            <hr>

            <div class="row">

                <div class="col">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <i class="bi bi-search mr-3 mt-2" style="cursor: pointer" data-toggle="modal"
                                data-target="#searchproduct"></i>
                        </div>
                        <input type="text" class="form-control @if($error_search) is-invalid @endif"
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
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive-md mb-2 ml-2">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Código</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Precio Unit.</th>
                                <th scope="col">Cant.</th>
                                <th scope="col">Total</th>
                                <th scope="col" class="text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->productssale as $key => $product )
                            <tr>
                                <th scope="row">{{ $loop->iteration }} </th>
                                <td>{{ $product['code'] }}</td>
                                <td>{{ $product['name'] }}</td>
                                <td>$ {{number_format($product['price'],0)}}</td>
                                <td>{{ $product['quantity'] }}</td>
                                <td>$ {{number_format($product['total'],0) }}</td>
                                <td class="text-end">
                                    <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                                        <div class="btn-group-sm mr-2" role="group" aria-label="First group">
                                            @include('popper::assets')

                                            <button type="button" class="btn btn-outline-primary"
                                                wire:click="modificarpreciounitario({{ $key }}, {{ $product['price'] }}, {{ $product['quantity'] }}, {{ $product['total'] }})"><i
                                                    class="bi bi-pencil-square"></i></button>
                                            <!-- Editar -->

                                            <button type="button" class="btn btn-outline-success"
                                                wire:click="addQuantity({{ $key }})"><i class="bi bi-plus-lg"></i>
                                                <!-- Incrementar --></button>

                                            <button type="button" class="btn btn-outline-secondary"
                                                wire:click="downCantidad({{ $key }})"><i
                                                    class="bi bi-dash-lg"></i></button> <!-- Restar -->

                                            <button @popper(Eliminar) type="button" class="btn btn-outline-danger"
                                                wire:click="destroyItem({{ $key }})"><i class="bi bi-trash3"></i>
                                            </button> <!-- Eliminar -->
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="8">
                                    <p>Agregue un producto para empezar...</p>
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

            </div>

        </div>
        <div class="card-footer">
            @if (session()->has('message'))
            <div class="alert alert-danger mt-4">
                {{ session('message') }}
            </div>
        @endif
            <div class="row mb-4">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">

                    <div class="row align-items-end float-right">
                        <div class="col-auto">
                          <label for="inputPassword6" class="col-form-label">Total</label>
                        </div>
                        <div class="col-auto">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control mt-3" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg" wire:model.lazy = "total_venta" autocomplete="off" disabled readonly>
                                @error('total')
                                <span class="text-danger">{{ $message }}</span>
                    @enderror
                              </div>
                        </div>
                      </div>

                      <div class="row align-items-end float-right">
                        <div class="col-auto">
                          <label for="inputPassword6" class="col-form-label">Abono</label>
                        </div>
                        <div class="col-auto">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control mt-3" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg" wire:model.lazy = "abono" autocomplete="off">
                              </div>
                        </div>
                      </div>


                      <div class="row align-items-end float-right">
                        <div class="col-auto">
                          <label for="inputPassword6" class="col-form-label">Saldo</label>
                        </div>
                        <div class="col-auto">
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control mt-3" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg" wire:model.lazy = "saldo" disabled readonly>
                              </div>
                        </div>
                      </div>
                      <div class="row align-items-end float-right mt-3">
                        <div class="col-auto">
                          <label for="inputPassword6" class="col-form-label">Método de pago</label>
                        </div>
                        <div class="col-auto">
                            <div class="input-group input-group-lg">
                                @include('includes.metodospago')
                            </div>
                        </div>
                        @error('metodo_pago')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                      </div>


                </div>
            </div>
            <div class="row mb-4">
                <div class="col-sm-8">
                </div>
                <div class="col-sm-4">
                    <x-adminlte-button label="Generar orden" theme="primary" icon="fas fa-check" class="float-right" wire:click="save" />
                </div>

            </div>

        </div>
    </div>
    @include('modals.orders.edit')
    @include('modals.mantenimiento.equipos.equipo')
    @include('modals.mantenimiento.equipos.searchequipos')
</div>
<script>
        function redirigir() {
            location.replace('{!! Config::get('app.URL') !!}/orders');
        }
    </script>

<script>
    window.addEventListener('close-modal', event => {
                     //alert('Hola mundo');
                        $('#modalEquipos').hide();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    })
</script>
