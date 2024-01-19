<div>
    <div class="card-body">
        <table class="table table-striped" id="tabProducts">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Factura</th>
                    <th>Precio compra</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($historial_compras as $compra)
                <tr>
                   <td>{{ $compra->purchase->created_at }} </td>
                   <td>{{ $compra->purchase->provider->name }} </td>
                   <td>{{ $compra->purchase->invoice }} </td>
                   <td>{{ $compra->purchase_price }} </td>
                   <td>{{ $compra->quantity }} </td>
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
