@extends('adminlte::page')

@section('scripts_head')
    @include('includes.head')
@stop

@section('title', 'Dashboard')

@section('content_header')
<x-adminlte-button label="Nuevo equipo" theme="primary" icon="fas fa-plus" data-toggle="modal" data-target="#modalEquipos" class="float-right"/>
    <h1>Equipos o dispositivos registrados</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="tabEquipos">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>Modelo o Referencia</th>
                        <th>Serial o Placa</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($equipos as $equipo)
                        <tr>
                            <td>{{ $equipo->tipoequipos->name }}</td>
                            <td>{{ $equipo->brand->name }}</td>
                            <td>{{ $equipo->referencia }}</td>
                            <td>{{ $equipo->placa }}</td>

                            </td>
                            <td>
                            {{-- <a class="btn btn-outline-success btn-sm" href="#" role="button"><i
                                    class="bi bi-list-stars"></i></a> --}}
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

    @include('modals.equipos.create')

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>


@stop
