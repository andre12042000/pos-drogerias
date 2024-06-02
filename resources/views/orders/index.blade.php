@extends('adminlte::page')

@section('scripts_head')
    @include('includes.head')
@stop

@section('title', 'Ordenes ')

@section('content_header')

    <div class=" mr-1">
        <div class="row">
            <div class="col-lg-10">
                <h3>Ordenes De Trabajo</h3>
            </div>
            <div class="col-lg-2 ">
                <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
                    aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Pos</li>
                        <li class="breadcrumb-item">Ordenes</li>
                        <li class="breadcrumb-item active" aria-current="page">Listado</li>
                    </ol>
                    </ol>
                </nav>
            </div>
        </div>


    </div>
    <br>

@stop



@section('content')

    @include('popper::assets')
    @livewire('orders.list-orden-component')

    <!-- Modal para crear categoría -->
    @include('modals.abono.abono')
    @include('modals.orders.create')
    @include('modals.mantenimiento.equipos.equipo')
    @include('modals.mantenimiento.equipos.searchequipos')
    @include('modals.client.create')
    @include('modals.client.search')

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>


@stop
