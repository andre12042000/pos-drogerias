@extends('adminlte::page')

@section('scripts_head')
    @include('includes.head')
@stop

@section('title', 'Impresoras')


@section('content_header')


@stop

@section('content')
@include('popper::assets')

    <div class="card card-info mt-5">
    <div class="card-header">  <h3>Nuestra impresora</h3></div>
        <div class="card-body">

            <table class="table table-striped">
                <thead class="">
                    <tr>
                        <th>Nombre</th>
                        <th>Estado</th>


                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($impresoras as $empresa)
                    <tr>
                        <td>{{ $empresa->nombre}}</td>
                        <td>@if($empresa->predeterminada == 1 ) <span class="badge bg-success">ACTIVA</span>  @else <span class="badge bg-danger">INACTIVA</span>  @endif</td>


                        <td class="text-right">
                            <a class="btn btn-outline-dark" href="{{ route('preubarecibo') }}" title="Prueba impresora"><i class="bi bi-receipt-cutoff"></i></a>
                            <a @popper(Actualizar) class="btn btn-outline-success " href="#" role="button"
                                data-toggle="modal" data-target="#editarEmpresaModal"><i
                                    class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="2">
                            <p>No se encontraron registros...</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para crear cliente -->

    @include('modals.client.create')





@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>


@stop
