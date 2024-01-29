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
                                    <td class="text-end">{{ $product->inventario->cantidad_caja }}</td>
                                    <td class="text-end">{{ $product->inventario->cantidad_blister }} </td>
                                    <td class="text-end">{{ $product->inventario->cantidad_unidad }}</td>

                                    <td class="text-end"> $ {{number_format($product->precio_caja,  0)}}</td>
                                    <td class="text-end"> $ {{number_format($product->precio_blister,  0)}}</td>
                                    <td class="text-end"> $ {{number_format($product->precio_unidad,  0)}}</td>
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
                                        role="button" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-plus-slash-minus"></i>
                                    </a>
                                @endcan



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

             var tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);

            // Formatear la fecha en el formato yyyy-mm-dd
            var tomorrowFormatted = tomorrow.toISOString().split('T')[0];

            // Establecer la fecha mínima en el campo de fecha
            document.getElementById("fecha_vencimiento_ajuste_inventario").min = tomorrowFormatted;

            // Función para crear y añadir una opción a un select
            function agregarOpcion(select, value, text, selected = false) {
                var option = document.createElement('option');
                option.value = value;
                option.text = text;
                select.appendChild(option);
                if (selected) {
                    option.selected = true;
                }
            }

            // Función para limpiar las opciones de un select
            function limpiarSelect(select) {
                select.innerHTML = '';
            }


            function llenarSelectPresentacion(select, array, selectedId, additionalAttributes) {
                limpiarSelect(select);
                agregarOpcion(select, '', 'Selecciona una opción');

                array.forEach(function(item) {
                    // Crear un objeto de opción con atributos adicionales
                    var optionAttributes = {
                        value: item.id,
                        text: item.name,
                        selected: item.id === selectedId,
                        // Agregar atributos adicionales
                        por_caja: item.por_caja,
                        por_blister: item.por_blister,
                        por_unidad: item.por_unidad
                    };

                    // Agregar la opción al select
                    agregarOpcion(select, optionAttributes);
                });
            }

            function agregarOpcion(select, optionAttributes) {
                var option = document.createElement('option');
                option.value = optionAttributes.value;
                option.text = optionAttributes.text;
                option.selected = optionAttributes.selected || false;

                // Agregar atributos adicionales
                option.por_caja = optionAttributes.por_caja;
                option.por_blister = optionAttributes.por_blister;
                option.por_unidad = optionAttributes.por_unidad;

                select.appendChild(option);
            }

            // Llenar selects con datos
            llenarSelectPresentacion(document.getElementById('presentacionesSelect'), presentaciones, product
                .presentacion_id);


            // Mostrar los datos en el contenido del modal
            document.getElementById('inputId').value = product.id;
            document.getElementById('inputCodigo').value = product.code;
            document.getElementById('inputName').value = product.name;
            document.getElementById('inputStockMinimo').value = product.stock_min;


            //Pasamos datos a los select excepto a Presentacion

            var categoriaSelect = document.getElementById('categoriaSelect');
            var subcategoriaSelect = document.getElementById('subcategoriaSelect');
            var ubicacionesSelect = document.getElementById('ubicacionesSelect');
            var laboratoriosSelect = document.getElementById('laboratoriosSelect');

               // Limpiar opciones existentes en el select
            categoriaSelect.innerHTML = '';
            subcategoriaSelect.innerHTML = '';
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




            // Mostrar el modal
            modal.style.display = 'block';
        }


        function cerrarModal() {
            // Cerrar el modal
            var modal = document.getElementById('stockModal');
            modal.style.display = 'none';
        }

        window.addEventListener('alert-registro-actualizado', () => {
            Swal.fire({
                icon: "success",
                title: "Registro actualizado correctamente",
                showConfirmButton: false,
                timer: 1500
            });


        });

        window.addEventListener('alert-error', event => {
            const {
                errorCode
            } = event.detail;
            console.log(event);
            Swal.fire({
                icon: "error",
                title: "Oops... ocurrió un error",
                text: `Código del error: ${errorCode}`,
            });
        });
    </script>
@stop
