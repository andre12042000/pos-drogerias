@extends('adminlte::page')

@section('scripts_head')
@include('includes.head')
@stop

@section('title', 'Empresas')

@section('scripts_head')
@include('includes.head')
@stop

@section('content_header')

<div class="row float-right mr-1">

    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Configuración</li>
            <li class="breadcrumb-item active" aria-current="page">Empresa</li>
        </ol>
    </nav>
</div>
<br>

@stop

@section('content')

@include('popper::assets')
@include('includes.alert')




<div class="card  card-info mt-2">
    <div class="card-header">
        <h3>Mi Empresa</h3>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead class="">
                <tr>
                    <th>Nombre</th>
                    <th>Nit</th>
                    <th>Telefono</th>
                    <th>Correo electrónico</th>
                    <th>Pref. facturación</th>
                    <th>Pref. servicio</th>
                    <th>Pref. orden</th>
                    <th>Pref. abono</th>
                    <th>Logo</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($empresas as $empresa)
                <tr>
                    <td>{{ ucwords($empresa->name) }}</td>
                    <td>{{ ($empresa->nit) }}</td>
                    <td>{{ ($empresa->telefono) }}</td>
                    <td>{{ ($empresa->email) }}</td>
                    <td>{{ ($empresa->pre_facturacion) }}</td>
                    <td>{{ ($empresa->pre_servicio) }}</td>
                    <td>{{ ($empresa->pre_orden) }}</td>
                    <td>{{ ($empresa->pre_abono) }}</td>
                    <td>
                        @if ($empresa->image)
                            <img src="{!! Config::get('app.URL') !!}/storage/{{ $empresa->image }}" width="50px"
                        height="50px">
                        @else
                            <img src="{!! Config::get('app.URL') !!}/img/sinimagen.jpg"
                                width="50px">
                        @endif
                    </td>
                    <td class="text-center">
                        <a @popper(Actualizar) class="btn btn-outline-success btn-sm" href="#" role="button"
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

@include('modals.empresa.create')

@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')



<script>
    console.log('Hi!');
</script>

<script>
    function setFocusName()
        {
            $('#name').focus();
        }

        $("#editarEmpresaModal").on('shown.bs.modal', function()
        {
            setTimeout(setFocusName, 0);
        });
</script>


@stop
