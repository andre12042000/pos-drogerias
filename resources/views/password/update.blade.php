@extends('adminlte::page')

@section('title', 'Cambio contraseña')

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

@stop

@section('content_header')

<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Para continuar</strong> debes actualizar tu contraseña!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>

@stop

@section('content')

<div class="card">
    <div class="card-header bg-info">
        <h4>Cambio de contraseña</h4> </div>

        <div class="card-body ">
            @livewire('change-password-component')
        </div>

</div>

@stop



@section('js')

@stop
