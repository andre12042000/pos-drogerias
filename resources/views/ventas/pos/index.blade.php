@extends('adminlte::page')

@section('scripts_head')
    @include('includes.head')
@stop

@section('title', 'Ventas')

@section('content_header')

@stop

@section('content')
<br>
   @include('popper::assets')
   @livewire('sale.sale-component')
   @include('modals.client.search')
   @include('modals.client.create')
   @include('modals.products.search')


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Abrir el modal automáticamente si se cumple la condición
        @if ($empresa->modal_aut_produc == 1)
            $('#searchproduct').modal('show');
        @endif

        // Agregar un evento de clic al botón de cierre del modal
        $('#closeModalButton').click(function() {
            $('#searchproduct').modal('hide');
        });
    });
</script>
    <script>
        console.log('Hi!');
    </script>
@stop
