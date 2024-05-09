 <h4><strong style="color: red">Reporte Generado Por Sistema FacilPos </strong></h4>

<br>
<div class="table-responsive col-md-12">
    <table class="table table-striped" >
        <thead>
            <tr>
                <th> <strong>Producto</strong> </th>
                <th> <strong>Cantidad Cajas</strong> </th>
                <th> <strong>Cantidad Blister</strong> </th>
                <th> <strong>Cantidad Unidad</strong> </th>
                <th> <strong>Cantidad Por Caja</strong> </th>
                <th> <strong>Cantidad Por Blister</strong> </th>
                <th> <strong>Cantidad Por Unidad</strong> </th>

                <th> <strong>Contenido Interno Por Caja</strong> </th>
                <th> <strong>Contenido Interno Por Blister</strong> </th>
                <th> <strong>Contenido Interno Por Unidad</strong> </th>

            </tr>
        </thead>
        <tbody>
            @forelse ($productos as $producto)

                    <tr >
                        <td>{{ ucwords($producto->product->name) }}</td>
                        <td>{{ $producto->cantidad_caja }}</td>
                        <td>{{ $producto->cantidad_blister }}</td>
                        <td>{{ $producto->cantidad_unidad }}</td>
                        <td>{{ $producto->product->presentacion->por_caja }}</td>
                        <td>{{ $producto->product->presentacion->por_blister }}</td>
                        <td>{{ $producto->product->presentacion->por_unidad }}</td>
                        <td>@if($producto->product->contenido_interno_caja == null)
                            0
                            @else
                            {{ $producto->product->contenido_interno_caja }}
                            @endif
                        </td>
                        <td>@if($producto->product->contenido_interno_blister == null)
                            0
                            @else
                            {{ $producto->product->contenido_interno_blister }}
                            @endif</td>
                        <td>@if($producto->product->contenido_interno_unidad == null)
                            0
                            @else
                            {{ $producto->product->contenido_interno_unidad }}
                            @endif</td>
                    </tr>
            @empty
                <tr>
                    <td colspan="4">
                        <p>No se encontraron registros...</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
