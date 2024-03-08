<div>
    <div class="row py-5">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped" id="productos-container">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- derecha --}}
        <div class="col-lg-7">
            <div class="card">
                <div x-data class="mt-2">
                    @include('popper::assets')

                    <div class="input-group  float-right col-5">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input @popper(Buscador) type="text" class="form-control" placeholder="Buscar producto"
                            aria-label="Username" aria-describedby="basic-addon1" wire:model="buscar">
                    </div>


                </div>
                <div class="modal-body">
                    <table class="table table-striped" id="productos-container">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Ubicación</th>
                                <th>Stock</th>
                                <th>Laboratorio</th>
                                <th>P. Caja</th>
                                <th>P. Blister</th>
                                <th>P. Unidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr data-product='{{ json_encode($product) }}'>
                                    <td>{{ $product->code }} </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->ubicacion->name }}</td>
                                    <td class="text-center">
                                        @if (
                                            $product->inventario->cantidad_caja == 0 &&
                                                $product->inventario->cantidad_blister == 0 &&
                                                $product->inventario->cantidad_unidad == 0)
                                            <i class="bi bi-stop-circle text-danger" data-bs-toggle="tooltip"
                                                data-html="true" data-bs-placement="top"
                                                title="No hay productos disponibles." style="cursor: not-allowed;"></i>
                                        @else
                                            <?php
                                            $tooltipContent = 'Cajas: ' . $product->inventario->cantidad_caja . ',   ' . 'Blisters: ' . $product->inventario->cantidad_blister . ',   ' . 'Unidades: ' . $product->inventario->cantidad_unidad;
                                            ?>
                                            <i class="bi bi-check-circle text-success" data-bs-toggle="tooltip"
                                                data-html="true" data-bs-placement="top" title="{{ $tooltipContent }}"
                                                style="cursor: not-allowed;"></i>
                                        @endif
                                    </td>

                                    <td>
                                        @isset($product->laboratorio->name)
                                            {{ Illuminate\Support\Str::limit($product->laboratorio->name, 12) }}
                                        @else
                                            N/R
                                        @endisset
                                    </td>

                                    <td class="price-cell text-end" data-option="disponible_caja"
                                    style="cursor: pointer" onclick="handlePriceClick(this)">
                                    {{ $product->precio_caja > 0 ? '$' . number_format($product->precio_caja, 0, ',', '.') : '0' }}
                                </td>
                                <td class="price-cell text-end" data-option="disponible_blister"
                                    style="cursor: pointer" onclick="handlePriceClick(this)">
                                    {{ $product->precio_blister > 0 ? '$' . number_format($product->precio_blister, 0, ',', '.') : '0' }}
                                </td>
                                <td class="price-cell text-end" data-option="disponible_unidad"
                                    style="cursor: pointer" onclick="handlePriceClick(this)">
                                    {{ $product->precio_unidad > 0 ? '$' . number_format($product->precio_unidad, 0, ',', '.') : '0' }}
                                </td>

{{--
                                    <td class="price-cell text-end" data-option="disponible_caja"
                                        style="cursor: pointer">
                                        {{ $product->precio_caja != 0 ? '$' . number_format($product->precio_caja, 0, ',', '.') : '0' }}
                                    </td>
                                    <td class="price-cell text-end" data-option="disponible_blister"
                                        style="cursor: pointer">
                                        {{ $product->precio_blister != 0 ? '$' . number_format($product->precio_blister, 0, ',', '.') : '0' }}
                                    </td>
                                    <td class="price-cell text-end" data-option="disponible_unidad"
                                        style="cursor: pointer">
                                        {{ $product->precio_unidad != 0 ? '$' . number_format($product->precio_unidad, 0, ',', '.') : '0' }}
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <p>No se encontraron registros...</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body float-right">

                    <div class="row g-3  ">

                        <div class="col-7 text-end">
                            <label for="inputPassword6" class="col-form-label">Total</label>
                        </div>
                        <div class="col-5">
                            <input type="text" id="inputPassword6" class="form-control"
                                aria-describedby="passwordHelpInline" disabled readonly>
                        </div>

                    </div>
                </div>


            </div>
        </div>

    </div>
</div>

@section('css')

    <style>
        /* Estilo para la tabla */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        /* Estilo para las celdas del encabezado */
        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        /* Estilo para las filas impares */
        .table tbody tr:nth-child(odd) {
            background-color: #f8f9fa;
        }

        /* Estilo para el mensaje cuando no hay registros */
        .table tbody tr td[colspan="6"] {
            text-align: center;
            padding: 10px;
        }
    </style>

@stop

@section('js')
    <script>
      function handlePriceClick(cell) {
        var priceText = cell.innerText.trim();
            var priceValue = parseFloat(priceText.replace(/[^\d.-]/g, ''));

            if (!isNaN(priceValue) && priceValue > 0) {
                var selectedOption = cell.dataset.option;
                var productData = JSON.parse(cell.closest('tr').dataset.product);

                console.log(productData);
            }
        }
    </script>
@stop
