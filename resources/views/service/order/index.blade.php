@extends('adminlte::page')

@section('scripts_head')
    @include('includes.head')
@stop

@section('title', 'Servicio técnico')

@section('content_header')

<a  href="{{ route('servicio.order.create') }}"  icon="fas fa-plus"  class="btn btn-primary float-right"> <i class="fas fa-plus"></i> Nueva orden de servicio</a>
    <h1>Servicio técnico</h1>
@stop

@section('content')

@include('popper::assets')
@livewire('service.list-component')



@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
