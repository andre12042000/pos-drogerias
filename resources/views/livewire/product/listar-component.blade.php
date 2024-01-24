<div>
    <div> @include('includes.alert')</div>
    @include('popper::assets')
    <div class="card card-info mt-2">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-3 ">
                    <h3>Productos</h3>
                </div>


                <div class="col-sm-9">
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
                        @can('Acceso Inventraio Crear')
                            <button type="button" class="btn btn-outline-light float-right ml-2" data-toggle="modal"
                                data-target="#productomodal">Nuevo producto <i class="las la-plus-circle"></i></button>
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
                            <td class="text-end">{{ $product->contenido_interno_caja }}</td>
                            <td class="text-end">{{ $product->contenido_interno_blister }} </td>
                            <td class="text-end">{{ $product->contenido_interno_unidad }}</td>

                            <td class="text-end">@money($product->precio_caja, 'COP', 0)</td>
                            <td class="text-end">@money($product->precio_blister, 'COP', 0)</td>
                            <td class="text-end">@money($product->precio_unidad, 'COP', 0)</td>
                            <td>
                                @if ($product->status == 'ACTIVE')
                                    <span class="badge badge-pill badge-success">Activo</span>
                                @else
                                    <span class="badge badge-pill bg-dark">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @can('Acceso Inventario Editar')
                                    <a @popper(Actualizar) class="btn btn-outline-primary btn-sm" data-toggle="modal"
                                        data-target="#productomodal" wire:click="sendData( {{ $product }} )"><i
                                            class="bi bi-pencil-square"></i></a>
                                @endcan


                                @can('Acceso Inventario Corregir')
                                    <!-- Button trigger modal -->
                                    <a onclick="abrirModal(
                                        {{ json_encode($product) }},
                                        {{ json_encode($categorias) }},
                                        {{ json_encode($subcategorias) }},
                                        {{ json_encode($presentaciones) }},
                                        {{ json_encode($ubicaciones) }},
                                        {{ json_encode($laboratorios) }}


                                    )"
                                        role="button" class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-plus-slash-minus"></i>
                                    </a>
                                @endcan

                                <button @popper(Eliminar) class="btn btn-outline-danger btn-sm"
                                    wire:click="destroy( {{ $product->id }} )"><i class="bi bi-trash3"></i></button>

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

    <script>
        function abrirModal(product, categorias, subcategorias, presentaciones, ubicaciones, laboratorios) {
            var modal = document.getElementById('stockModal');
            var contenidoModal = document.getElementById('contenidoModal');

            document.getElementById('inputCodigo').value = product.code;
            document.getElementById('inputName').value = product.name;
            document.getElementById('inputStockMinimo').value = product.stock_min;
            document.getElementById('inputStockMaximo').value = product.stock_max;
            document.getElementById('inputIva').value = product.iva_product;
            document.getElementById('inputCanCaja').value = product.contenido_interno_caja;
            document.getElementById('inputCanBlister').value = product.contenido_interno_blister;
            document.getElementById('inputCanUnidad').value = product.contenido_interno_unidad;
            document.getElementById('inputCosCaja').value = product.costo_caja;
            document.getElementById('inputCosBlister').value = product.costo_blister;
            document.getElementById('inputCosUnidad').value = product.costo_unidad;
            document.getElementById('inputPreCaja').value = product.precio_caja;
            document.getElementById('inputPreBlister').value = product.precio_blister;
            document.getElementById('inputPreUnidad').value = product.precio_unidad;






            var categoriaSelect = document.getElementById('categoriaSelect');
            var subcategoriaSelect = document.getElementById('subcategoriaSelect');
            var presentacionesSelect = document.getElementById('presentacionesSelect');
            var ubicacionesSelect = document.getElementById('ubicacionesSelect');
            var laboratoriosSelect = document.getElementById('laboratoriosSelect');

            // Limpiar opciones existentes en el select
            categoriaSelect.innerHTML = '';
            subcategoriaSelect.innerHTML = '';
            presentacionesSelect.innerHTML = '';
            ubicacionesSelect.innerHTML = '';
            laboratoriosSelect.innerHTML = '';


            // Crear opciones para el select basadas en las categorías proporcionadas
            var defaultLaboratoriosOption = document.createElement('option');
            defaultLaboratoriosOption.value = '';
            defaultLaboratoriosOption.text = 'Selecciona una opción';
            laboratoriosSelect.appendChild(defaultLaboratoriosOption);

            laboratorios.forEach(function(laboratorios) {
                var option = document.createElement('option');
                option.value = laboratorios.id;
                option.text = laboratorios.name;
                laboratoriosSelect.appendChild(option);
            })


            var defaultcategoriaOption = document.createElement('option');
            defaultcategoriaOption.value = '';
            defaultcategoriaOption.text = 'Selecciona una opción';
            categoriaSelect.appendChild(defaultcategoriaOption);

            categorias.forEach(function(categoria) {
                var option = document.createElement('option');
                option.value = categoria.id;
                option.text = categoria.name;
                categoriaSelect.appendChild(option);
            });

            var defaultSubcategoriaOption = document.createElement('option');
            defaultSubcategoriaOption.value = '';
            defaultSubcategoriaOption.text = 'Selecciona una opción';
            subcategoriaSelect.appendChild(defaultSubcategoriaOption);

            subcategorias.forEach(function(subcategoria) {
                var option = document.createElement('option');
                option.value = subcategoria.id;
                option.text = subcategoria.name;
                subcategoriaSelect.appendChild(option);
            });

            var defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = 'Selecciona una opción';
            presentacionesSelect.appendChild(defaultOption);

            // Iterar sobre las presentaciones y agregar opciones
            presentaciones.forEach(function(presentacion) {
                var option = document.createElement('option');
                option.value = presentacion.id;
                option.text = presentacion.name;
                presentacionesSelect.appendChild(option);
            });

            var defaultUbicacionOption = document.createElement('option');
            defaultUbicacionOption.value = '';
            defaultUbicacionOption.text = 'Selecciona una opción';
            ubicacionesSelect.appendChild(defaultUbicacionOption);

            ubicaciones.forEach(function(ubicaciones) {
                var option = document.createElement('option');
                option.value = ubicaciones.id;
                option.text = ubicaciones.name;
                ubicacionesSelect.appendChild(option);
            });

            console.log('Categorías:', categorias);

            // Mostrar los datos en el contenido del modal
            //  contenidoModal.innerHTML = '<p>ID: ' + product.id + '</p><p>Codigo: ' + product.code + '</p>';

            // Mostrar el modal
            modal.style.display = 'block';
        }

        function cerrarModal() {
            // Cerrar el modal
            var modal = document.getElementById('stockModal');
            modal.style.display = 'none';
        }
    </script>
@stop
