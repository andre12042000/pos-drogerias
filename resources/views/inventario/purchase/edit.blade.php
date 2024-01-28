@extends('adminlte::page')

@section('scripts_head')
    @include('includes.head')
@stop

@section('title', 'Editar factura')

@section('content_header')

<div class="row float-right mr-1">

    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Inventario</li>
            <li class="breadcrumb-item">Compras</li>
            <li class="breadcrumb-item active" aria-current="page">Detalles</li>
        </ol>
    </nav>
</div>
<br>

@stop

{{-- @section('content_header')

<a class="btn btn-primary btn -sm float-right" href="{{ URL::previous() }}"><i class='bi bi-arrow-left-circle'></i> Atrás</a>
    <h1>Editar factura de compra</h1>
@stop --}}

@section('content')

    @include('popper::assets')
    @include('includes.alert')

@livewire('purchase.edit-component', ['purchase' => $purchase])

   <!-- Modal para crear producto -->
   @include('modals.products.create')
   @include('modals.purchase.search-products')


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

<script>
    Livewire.on('calcularDatosEvent', (data) => {
        console.log('Evento personalizado recibido:');
        // Aquí puedes realizar acciones adicionales con la información recibida
    });
</script>

<script>
    $(document).ready(function(){
        $('#searchproductsemodal').modal('show'); // Abre el modal al cargar la página
    });
</script>

<script>

    function calcularedit()
    {
        var preciocompra = parseInt(document.getElementById('preciocompra').value);
        var porcentaje = parseInt(document.getElementById('porcentaje').value);
        var resultado = parseInt(preciocompra + ((preciocompra * porcentaje)/100));
        document.getElementById("precioventa").value = resultado;

        Livewire.emit('precioventavalidate', resultado)
    }
    function calcular()
    {

        var preciocompra = parseInt(document.getElementById('preciocompra').value);
        var porcentaje = parseInt(document.getElementById('porcentaje').value);
        var resultado = parseInt(preciocompra + ((preciocompra * porcentaje)/100));
        document.getElementById("precioventa").value = resultado;



        Livewire.emit('precioventavalidate', resultado)
    }



</script>
@stop
