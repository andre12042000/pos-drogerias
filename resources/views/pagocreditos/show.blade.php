@extends('adminlte::page')

@section('scripts_head')
    @include('includes.head')
@stop

@section('content_header')

@stop

@section('title', 'Marcas')



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
                                <img src="{!! Config::get('app.URL') !!}/img/sinimagen.jpg" width="150px"
                                    class="mt-0">
                            @endif
                        </div>
                        <div class="col-lg-8">
                            <br>
                            <label style=" text-transform: capitalize !important;"
                                class="mt-2">{{ $empresa->name }}</label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 text-center"> Nit: {{ $empresa->nit }}  - {{$empresa->dv}}<br>
                    Tel: {{ $empresa->telefono }} <br>
                    {{ $empresa->email }}
                    @isset($empresa->direccion)
                        <br> {{ $empresa->direccion }}
                    @endisset

                </div>
                <div class="col-lg-3 float-right text-right">
                    <br>
                    <div class="dropdown mr-4">
                        <a class="alert-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Generar Recibo
                        </a>

                        <ul class="dropdown-menu text-dark" aria-labelledby="dropdownMenuLink">
                            {{-- <li> <a class="dropdown-item text-dark" href="{{ route('ventas.pos.pdf', $venta) }}"><i
                                        class="bi bi-download"></i> Descargar PDF </a></li> --}}
                            <li> <a href="{{ route('imprimir.pago_venta_credito', $recibo->id) }}"
                                    class="dropdown-item text-dark"> <i class="bi bi-printer"></i> Imprimir recibo</a></li>

                        </ul>
                    </div>
                </div>

            </div>
        </div>



    <div class="row mt-4">
        <div class="col-lg-4">
            <h4 class="text-center"><strong>Información Básica</strong></h4>
            <ul class="mt-5">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="nombre"><strong>Recibo Nro.</strong></label>
                    <p class="text-bold">{{$recibo->full_nro }}</p>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="nombre"><strong>Cliente</strong></label>
                    <p>{{ mb_strtoupper($recibo->client->name)}}</p>
                </li>
                 <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="num_compra"><strong>Identificación</strong></label>
                    <p> @if($recibo->client->number_document != '') {{$recibo->client->number_document}} @else <span> Sin ingresar </span> @endif</p>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="num_compra"><strong>Fecha de pago</strong></label>
                    <p> {{ \Carbon\Carbon::parse($recibo->created_at)->format('d M Y') }} </p>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="num_compra"><strong>Vendedor</strong></label>
                    <p>{{  mb_strtoupper($recibo->user->name)}}</p>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="num_compra"><strong>Método de pago</strong></label>
                    <p>{{  mb_strtoupper($recibo->metodopago->name)}}</p>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <label class="form-control-label" for="num_compra"><strong>TOTAL PAGADO</strong></label>
                    <h4>$ {{  number_format($recibo->valor ,0)}}</h4>
                </li>




            </ul>
        </div>

        <div class="col-lg-1"></div>

        <div class="col-lg-7">

            <div class="table-responsive">
            <h4 class="text-center"><strong> Conceptos de pago</strong></h4>

                <table id="detalles" class="table">
                    <thead>
                        <tr>
                            <th class="text-center">Compra Nro.</th>
                            <th class="text-center">Total compra</th>
                            <th class="text-center">Fecha compra</th>
                            <th class="text-center">Saldo recibido</th>
                            <th class="text-center">Valor pagado</th>
                            <th class="text-center">Saldo restante</th>
                            <th class="text-center">Ver compra</th>
                        </tr>
                    </thead>
                    <tbody>
                         @foreach ($recibo->pagocreditodetalles as $detalle)
                            <tr>

                                <td class="text-center">{{ mb_strtoupper($detalle->credito->sale->full_nro) }}</td>
                                <td class="text-center">$ {{number_format( $detalle->credito->sale->total, 0)}}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($detalle->credito->sale->created_at)->format('d M Y') }}</td>
                                <td class="text-center">$ {{number_format( $detalle->saldo_recibido, 0)}}</td>
                                <td class="text-center">$ {{number_format( $detalle->valor_pagado, 0)}}</td>
                                <td class="text-center">$ {{number_format( $detalle->saldo_restante, 0)}}</td>
                                <td class="text-center">
                                    <a @popper(Ver compra) class="btn btn-outline-primary btn-sm" href="{{ route('ventas.pos.details', $detalle->credito->sale->id) }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>

                    </tfoot>
                </table>
            </div>
        </div>
    </div>




    @stop

    @section('css')
        <link rel="stylesheet" href="/css/admin_custom.css">
    @stop

    @section('js')

    @stop
