<div>
    <div class="card-body">
        <table class="table table-striped" id="tabProducts">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Factura</th>
                    <th>Precio de venta</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($historial_ventas as $venta)
                <tr>
                   <td>{{ $venta->sale->created_at }}</td>
                   <td>{{ $venta->sale->client->name }}</td>
                   <td>{{ $venta->sale->full_nro }}</td>
                   <td class="text-end">{{ $venta->price }}</td>
                   <td class="text-center">{{ $venta->quantity }}</td>

                </tr>
                @empty
                <tr>
                    <td colspan="5">No hay registros disponibles...</td>
                 </tr>

                @endforelse
            </tbody>
        </table>
    </div>

</div>
