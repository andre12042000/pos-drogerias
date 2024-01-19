@extends('adminlte::page')

@section('scripts_head')
    @include('includes.head')
@stop

@section('title', 'Crear orden')

@section('content_header')
<a href="{{ route('servicio.order') }}"  class=" btn btn-primary float-right"> <i class="far fa-arrow-alt-circle-left"></i> Atrás</a>
<h1>Servicio técnico</h1>
@stop

@section('content')

@include('popper::assets')
@livewire('service.create-component')

@include('modals.client.search')
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
