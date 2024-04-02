<div>
    <div> @include('includes.alert')</div>
    @include('popper::assets')
    <div class="card card-info mt-2">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-2 ">
                    <h3>Productos</h3>
                </div>


                <div class="col-sm-10">
                    <div class="input-group float-right">

                        <select wire:model="cantidad_registros" class="form-select col-sm-1 mr-2"
                            aria-label="Default select example">
                            <option value="10"> 10 </option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>

                        <select wire:model="filter_estado" class="form-select col-sm-2 mr-2"
                            aria-label="Default select example">
                            <option value=""> Filtrar estado </option>
                            <option value="ACTIVE">Activos</option>
                            <option value="DEACTIVATED">Inactivos</option>
                        </select>



                        <select wire:model="filter_category" class="form-select col-sm-4 mr-2"
                            aria-label="Default select example">
                            <option value="10"> Filtrar por categoría </option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                            @endforeach
                        </select>
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input @popper(Buscador) type="text" class="form-control" placeholder="Buscar producto"
                            aria-label="Username" aria-describedby="basic-addon1" wire:model="buscar">
                        @can('Acceso Producto Crear')
                            <button type="button" class="btn btn-outline-light float-right ml-2" data-toggle="modal"
                                data-target="#productomodal">Nuevo producto <i class="las la-plus-circle"></i></button>

                            <button type="button" id="redireccionarBtn"
                                class="btn btn-outline-light float-right ml-2">Nuevo Producto Elaborado <i
                                    class="bi bi-columns-gap"></i></button>
                        @endcan


                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="tabProducts">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Cant. caja</th>
                        <th>Cant. blister</th>
                        <th>Cant. unidad</th>
                        <th>Precio caja</th>
                        <th>Precio blister</th>
                        <th>Precio unidad</th>

                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productos as $product)
                        <tr>
                            <td>{{ $product->code }}</td>
                            <td><a href="{{ route('inventarios.product.show', $product->id) }}" target="_blank">
                                    {{ $product->producto }} </a></td>
                            <td class="text-end">{{ $product->inventario->cantidad_caja }}</td>
                            <td class="text-end">{{ $product->inventario->cantidad_blister }} </td>
                            <td class="text-end">{{ $product->inventario->cantidad_unidad }}</td>

                            <td class="text-end"> $ {{ number_format($product->precio_caja, 0) }}</td>
                            <td class="text-end"> $ {{ number_format($product->precio_blister, 0) }}</td>
                            <td class="text-end"> $ {{ number_format($product->precio_unidad, 0) }}</td>
                            <td>
                                @if ($product->status == 'ACTIVE')
                                    <span class="badge badge-pill badge-success">Activo</span>
                                @else
                                    <span class="badge badge-pill bg-dark">Inactivo</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @can('Acceso Producto Editar')
                                    @if ($product->is_combo == 1)
                                        <a @popper(Editar Combo) title="Editar Combo" class="btn btn-outline-success btn-sm"
                                            href="{{ route('combos.editar', $product->id) }}"><i
                                                class="bi bi-pencil-square"></i></a>
                                    @else
                                        <a onclick="modalEditarProducto({{ json_encode($product) }},
                                                                 {{ json_encode($categorias) }},
                                                                 {{ json_encode($subcategorias) }},
                                                                 {{ json_encode($presentaciones) }},
                                                                 {{ json_encode($ubicaciones) }},
                                                                 {{ json_encode($laboratorios) }},)"
                                            role="button" title="Editar Producto" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif
                                @endcan

                                @can('Acceso Producto Ajustar')
                                    <a onclick="modalAjuste({{ json_encode($product) }},
                                                        {{ json_encode($product->inventario) }},)"
                                        role="button" title="Ajustar Inventario" class="btn btn-outline-success btn-sm">
                                        <i class="bi bi-boxes"></i>
                                    </a>
                                @endcan
                                <a @popper(Eliminar Producto) class="btn btn-outline-danger btn-sm"
                                    wire:click="destroy( {{ $product->id }} )"><i class="bi bi-trash"></i></a>


                                @include('popper::assets')
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
        <div class="card-footer ">
            <nav class="" aria-label="">
                <ul class="pagination">
                    {{ $productos->links() }}
                </ul>
            </nav>

        </div>
    </div>
</div>

@section('js')

    <script src="{{ asset('js/productos/listarProductos.js') }}"></script>

    <script>
        // Obtener el botón por su ID
        var btn = document.getElementById('redireccionarBtn');

        // Agregar un evento de clic al botón
        btn.addEventListener('click', function() {
            // Redirigir a la página deseada

            window.location.href = '{{ route('inventarios.combo.create') }}';
        });
    </script>
@stop
