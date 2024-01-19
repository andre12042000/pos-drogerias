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
