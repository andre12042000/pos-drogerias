@extends('adminlte::page')

@section('scripts_head')
    @include('includes.head')
@stop

@section('title', 'Productos')

@section('content_header')

<div class="row float-right mr-1">

    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Inventario</li>
            <li class="breadcrumb-item">Productos</li>
            <li class="breadcrumb-item active" aria-current="page">Importar</li>
        </ol>
    </nav>
</div>
<br>
@stop



@section('content')
    @include('popper::assets')
    @include('includes.alert')

    <div class="card   card-danger">
        <div class="card-header">
            <h3> Importar productos</h3>
        </div>

        <div class="row m-4">
            <form class="row g-3" action="{{ route('inventarios.import.data') }}" method="post" enctype="multipart/form-data">
                @csrf
                    @if (Session::has('message'))
                        <p>{{ Session::get('message')}}</p>
                    @endif

                <div class="col-auto">
                    <input class="" type="file" id="file" name="file">
                </div>
                <div class="col-auto">
                  <button type="submit" class="btn btn-primary mb-3">Importar productos</button>
                </div>
              </form>

        </div>
        <div class="card-footer">
            <p class="lead">
               Este proceso puede demorar algunos minutos.
            </p>
        </div>


    </div>



@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>





@stop
