<div>
    @section('title', 'Crear Combo')

    @include('popper::assets')
    <div class="card card-info">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-8">
                    <h3>Crear Combo</h3>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-outline-light btn-sm mt-1 float-right" onclick="redirigir()"><i
                            class='bi bi-arrow-left-circle'></i> Atrás</button>
                </div>
            </div>
        </div>
        <div class="card-body">


            <div class="row">
                <div class="col-4">
                    <div class="row m-3">

                        <div class="form-floating">
                            <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                                wire:model.lazy = 'codigo' autocomplete="off">
                            <label for="floatingInput">Código</label>
                            @error('codigo')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mt-4">
                            <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                                wire:model.lazy = 'nombre' autocomplete="off">
                            <label for="floatingInput">Nombre</label>
                            @error('nombre')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-floating mt-4">
                            <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                                disabled readonly wire:model.lazy = 'costo_total'>
                            <label for="floatingInput">Costo</label>
                        </div>

                        <div class="form-floating mt-4">
                            <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                                wire:model.lazy = 'iva' disabled>
                            <label for="floatingInput">Iva</label>
                        </div>

                        <div class="form-floating mt-4">
                            <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com"
                                disabled readonly wire:model.lazy = 'precio_sugerido'>
                            <label for="floatingInput">Precio Sugerido</label>
                        </div>

                        <div class="form-floating mt-4 mb-4">
                            <input type="number" class="form-control" id="floatingInput" placeholder="name@example.com"
                                wire:model.defer = 'precio_venta' autocomplete="off">
                            <label for="floatingInput">Precio de venta</label>
                            @error('precio_venta')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div >

                            <label for="formFile" class="form-label">Imagen Combo</label>
                            <input class="form-control" type="file" id="imagen" name="imagen"
                                wire:model="imagen">
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if ($imagen != null || $imagen != '')
                        <div class="mb-4 mt-3 float-center text-center"> <img style="height: 100px; width: 100px;"
                                src="{{ $imagen->temporaryUrl() }}" alt="">
                        </div>
                    @endif

                        <div class="d-grid gap-2">
                            <hr>
                            <button class="btn btn-primary" type="button" style="height: 50px;" wire:click="save">Guardar</button>
                          </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0 mt-2"><strong>Detalles del combo</strong></h4>
                            <div class="float-right">
                                <button class="btn btn-outline-primary" type="button" data-toggle="modal"
                                    data-target="#productomodal">Nuevo producto <i class="bi bi-plus-circle"></i></button>

                                <button class="btn btn-outline-success" type="button" data-toggle="modal"
                                    data-target="#productoscombomodal">Añadir producto <i class="bi bi-search"></i></button>


                            </div>
                        </div>
                        <div class="table-responsive col-md-12">
                            <table class="table table-striped" id="tabProducts">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Código</th>
                                        <th>Nombre / Descripción</th>
                                        <th>Forma</th>
                                        <th>Cantidad</th>
                                        <th>Costo Unitario</th>
                                        <th>Total</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($productosCrearCombo as $index => $producto)
                                    <tr>
                                        <td></td>
                                        <td>{{ $producto['codigo'] }}</td>
                                        <td>{{ $producto['name'] }}</td>
                                        <td>{{ $producto['forma_presentacion'] }}</td>
                                        <td>{{ $producto['cantidad'] }}</td>
                                        <td>$ {{number_format($producto['costo_unitario'],0)}}</td>
                                        <td>$ {{number_format($producto['total'],0)}}</td>
                                        <td class="text-center"><i class="bi bi-trash text-danger" style="cursor: pointer" wire:click="eliminarProducto({{ $index }})"></i></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8"><p>No hay productos añadidos para mostrar...</p></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($error_cantidad_productos)
                            <span class="text-danger text-center">Para crear un combo, se requieren al menos 2 productos!</span>
                        @enderror


                    </div>




                </div>
            </div>

        </div>
        @include('modals.products.create')
        @include('modals.products.addproductcombo')
    </div>
</div>

<script>
    window.addEventListener('addProductArray', event => {
       console.log('olis');
    })
    </script>


@section('content_header')

    <div class="row float-right mr-1">

        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
            aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Inventarios</li>
                <li class="breadcrumb-item active" aria-current="page">Crear Combo</li>
            </ol>
        </nav>
    </div>

    <br>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

<script>
    function redirigir() {
        location.replace('{!! Config::get('app.URL') !!}/inventarios/productos');
    }
</script>


