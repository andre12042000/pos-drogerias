<div class="table-responsive">
    <h4 class="text-center"><strong> Historial pago de créditos</strong></h4>

    <table id="detalles" class="table">
        <thead>
            <tr>
                <th class="text-right">Código</th>
                <th class="text-center">Método de pago</th>
                <th class="text-right">Valor</th>
                <th class="text-center"> Fecha de pago</th>
                <th>Acciones</th>

            </tr>
        </thead>
        <tbody>
            @forelse ($pagocreditos as $pago)
                <tr>
                    <td class="text-center">{{ $pago->full_nro }} </td>
                    <td class="text-center">{{ $pago->metodopago->name }} </td>
                    <td class="text-end">$ {{ number_format($pago->valor, 0) }} </td>
                    <td class="text-end">{{ date('d/m/Y h:i', strtotime($pago->created_at)) }} </td>
                    <td><i class="bi bi-eye-fill"
                            style="font-size: 20px; margin-left: 20px; margin-right: 10px; cursor: pointer;"></i>
                        <i class="bi bi-printer-fill" style="font-size: 20px; cursor: pointer;" wire:click = 'imprimirPago({{ $pago->id }})' ></i>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center mt-4">
                        <p>No hay registros disponibles</p>
                    </td>
                </tr>
            @endforelse
        </tbody>


    </table>
</div>
