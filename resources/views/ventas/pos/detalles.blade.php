@extends('adminlte::page')

@section('scripts_head')
@include('includes.head')
@stop

@section('title', 'Facturación')

@section('content_header')

@stop

@section('content')
@include('popper::assets')
<br>
<div class="card card-info">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-5 mt-1">
                <div class="row">
                    <div class="col-lg-4 mt-2">
<br>
                        @if ($empresa->image)
                        <img src="{!! Config::get('app.URL') !!}/storage/{{ $empresa->image }}" width="150px" class="mt-0">
                    @else
                        <img src="{!! Config::get('app.URL') !!}/storage/livewire-tem/sin_image_product.jpg" width="150px" class="mt-0">
                    @endif
                    </div>
                    <div class="col-lg-8">
                        <br>
                        <label style=" text-transform: capitalize !important;" class="mt-2">{{$empresa->name}}</label>
                    </div>


                </div>

            </div>

            <div class="col-lg-4 text-center"> Nit: {{$empresa->nit}} <br>
                Tel: {{$empresa->telefono}} <br>
                 {{$empresa->email}}
                @isset($empresa->direccion)

                     <br> {{$empresa->direccion}}
                @endisset

            </div>
            <div class="col-lg-3 float-right text-right">
                <br>
                <div class="dropdown mr-4">
                    <a class="alert-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                      Generar Recibo
                    </a>

                    <ul class="dropdown-menu text-dark" aria-labelledby="dropdownMenuLink">
                      <li> <a class="dropdown-item text-dark"   href="{{ route('ventas.pos.pdf',  $venta) }}"><i class="bi bi-download"></i> Descargar PDF </a></li>
                      <li> <a href="{{ route('ventas.pos.imprimir.recibo', $venta) }}" class="dropdown-item text-dark" > <i class="bi bi-printer"></i> Imprimir recibo</a></li>

                    </ul>
                  </div>
            </div>

        </div>
    </div>

    @include('includes.bodyfactura')
    @stop

    @section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">

    @stop

    @section('js')
    <script>
        console.log('Hi!');
    </script>
    @stop
