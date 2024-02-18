<div>
    <h5>Error en parametro de unidad</h5>
    <table class="table">
        <thead>
          <tr>
            <th scope="col">Código</th>
            <th scope="col">Nombre</th>
            <th scope="col">Disponible Blister</th>
            <th scope="col">Cantidad Blister Caja</th>
            <th scope="col">Disponible Unidad</th>
            <th scope="col">Cantidad Unidad Caja</th>
            <th scope="col">Cant. Cajas</th>
            <th scope="col">Cant. Blister</th>
            <th scope="col">Cant. Inventario</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

    @foreach ($unidad as $unit)
    <tr>
        <td>{{ $unit->code }} </td>
        <td>{{ $unit->name }} </td>
        <td class="text-center">@if ($unit->disponible_blister > 0)
            Si
        @else
            No
        @endif</td>
        <td class="text-center">{{ $unit->contenido_interno_blister }} </td>
        <td class="text-center" style="background: rgb(250, 172, 172)">
            @if ($unit->disponible_unidad > 0)
            Si
        @else
            No
        @endif
        </td>
        <td class="text-center" style="background: rgb(250, 172, 172)"">{{ $unit->contenido_interno_unidad }} </td>
        <td class="text-center" style="background: rgb(237, 245, 215)">{{ $unit->inventario->cantidad_caja }} </td>
        <td class="text-center" style="background:  rgb(237, 245, 215)">{{ $unit->inventario->cantidad_blister }} </td>
        <td class="text-center" style="background: rgb(237, 245, 215)">{{ $unit->inventario->cantidad_unidad }} </td>
    </tr>
    @endforeach

    @foreach ($blisters as $unit)
    <tr>
        <td>{{ $unit->code }} </td>
        <td>{{ $unit->name }} </td>
        <td class="text-center" style="background: rgb(250, 172, 172)">@if ($unit->disponible_blister > 0)
            Si
        @else
            No
        @endif</td>
        <td class="text-center" style="background: rgb(250, 172, 172)">{{ $unit->contenido_interno_blister }} </td>
        <td class="text-center">
            @if ($unit->disponible_unidad > 0)
            Si
        @else
            No
        @endif
        </td>
        <td class="text-center">{{ $unit->contenido_interno_unidad }} </td>
        <td class="text-center" style="background: rgb(237, 245, 215)">{{ $unit->inventario->cantidad_caja }} </td>
        <td class="text-center" style="background:  rgb(237, 245, 215)">{{ $unit->inventario->cantidad_blister }} </td>
        <td class="text-center" style="background: rgb(237, 245, 215)">{{ $unit->inventario->cantidad_unidad }} </td>
    </tr>

    @endforeach

    @foreach ($SinInventario as $unit)
    <tr>
        <td>{{ $unit->code }} </td>
        <td>{{ $unit->name }} </td>
        <td class="text-center" style="background: rgb(250, 172, 172)">@if ($unit->disponible_blister > 0)
            Si
        @else
            No
        @endif</td>
        <td class="text-center" style="background: rgb(250, 172, 172)">{{ $unit->contenido_interno_blister }} </td>
        <td class="text-center">
            @if ($unit->disponible_unidad > 0)
            Si
        @else
            No
        @endif
        </td>
        <td class="text-center">{{ $unit->contenido_interno_unidad }} </td>
        <td class="text-center" style="background: rgb(237, 245, 215)">{{ $unit->inventario->cantidad_caja }} </td>
        <td class="text-center" style="background:  rgb(237, 245, 215)">{{ $unit->inventario->cantidad_blister }} </td>
        <td class="text-center" style="background: rgb(237, 245, 215)">{{ $unit->inventario->cantidad_unidad }} </td>
    </tr>

    @endforeach

    </tbody>
    </table>

@include('modals.products.corregirproducts')
</div>

<script>
    window.addEventListener('abrirModalEdicion', event => {
        // Obtener el modal por su ID
        var modal = new bootstrap.Modal(document.getElementById('corregirProductoModal'));

        // Mostrar el modal
        modal.show();
    });
</script>
