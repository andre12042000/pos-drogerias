@extends('adminlte::page')

@section('scripts_head')
@include('includes.head')
@stop

@section('title', 'Orden de trabajo')

@section('content_header')

@stop

@section('content')
@include('popper::assets')
<br>

<div class="card card-info">
    <div class="card-header">
        <div class="row">
            <div class="col-lg-4 mt-1">
                <div class="row">
                    <div class="col-lg-2 mt-2">
                        @if ($empresa->image)
                        <img src="{!! Config::get('app.URL') !!}/storage/{{ $empresa->image }}" width="50px"
                            class="mt-0">
                        @else
                        <img src="{!! Config::get('app.URL') !!}/storage/livewire-tem/sin_image_product.jpg"
                            width="50px" class="mt-0">
                        @endif
                    </div>
                    <div class="col-lg-10">
                        <h2 style=" text-transform: capitalize !important;" class="mt-2">{{$empresa->name}}</h2>
                    </div>
                </div>

            </div>

            <div class="col-lg-4 text-center"> Nit: {{$empresa->nit}} <br>
                Tel: {{$empresa->telefono}} <br>
                Correo: {{$empresa->email}}

            </div>
        </div>
    </div>
</div>

<div class="card-body">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                @if ($order->status == 1)
                <div class="ribbon-wrapper ribbon-lg">
                    <div class="ribbon bg-warning">
                      SOLICITADO
                    </div>
                </div>
                @else
                <div class="ribbon-wrapper ribbon-lg">
                    <div class="ribbon bg-info">
                      ENTREGADO
                    </div>
                </div>

                @endif

                <div class="card-header">
                    <h4 class="text-center"><strong>Información Básica</strong></h4>
                </div>
                <div class="card-body">


                    <ul class="mt-5">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <label class="form-control-label" for="nombre"><strong>Orden Nro.</strong></label>
                            <p class="text-bold">{{$order->full_nro }}</p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <label class="form-control-label" for="nombre"><strong>Cliente</strong></label>
                            <p>{{ucwords($order->client->name)}}</p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <label class="form-control-label" for="num_compra"><strong>Identificación</strong></label>
                            <p> @if($order->client->number_document != '') {{$order->client->number_document}} @else
                                <span> Sin ingresar </span> @endif
                            </p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <label class="form-control-label" for="num_compra"><strong>Fecha de
                                    solicitud</strong></label>
                            <p> {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }} </p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <label class="form-control-label" for="num_compra"><strong>Vendedor</strong></label>
                            <p>{{$order->user->name}}</p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <label class="form-control-label" for="num_compra"><strong>Proveedor</strong></label>
                            @if ($order->provider)
                                <p>{{$order->provider->name}}</p>
                            @else
                                <p>No asignado</p>
                            @endif

                        </li>
                    </ul>

                <hr>

                    <ul class="mt-5">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <label class="form-control-label" for="nombre"><strong>TOTAL</strong></label>
                            <p class="text-bold">$ {{number_format( $order->valor,0)}}</p>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <label class="form-control-label" for="nombre"><strong>ABONADO</strong></label>
                            <p class="text-bold">$ {{number_format( $order->abono,0)}}</p>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center @if ($order->saldo > 0)
                            list-group-item-danger
                        @else
                            list-group-item-info
                        @endif">
                            <label class="form-control-label" for="nombre"><strong>SALDO</strong></label>
                            <p class="text-bold">$ {{number_format( $order->saldo,0)}}</p>
                        </li>
                    </ul>


                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card" >
                <div class="card-header">
                    <h4 class="text-center"><strong>Detalles</strong></h4>
                </div>
                <div class="card-body" >
                    @if(!empty($order->descripcion))

                        <div class=" row">
                            <label  class="col-sm-2 col-form-label">Descripción</label>
                            <div class="col-sm-10">
                                <p class="form-control-plaintext" >{{ $order->descripcion }}</p>
                            </div>
                        </div>
                    @endif

                    @if($order->details->count() > 0)
                    <table id="detalles" class="table">
                        <thead>
                            <tr>
                                <th class="text-center">Producto</th>
                                <th class="text-right">Precio Unitario</th>
                                <th class="text-right">Cantidad</th>
                                <th class="text-right"> Subtotal</th>

                            </tr>
                        </thead>
                        <tbody> @foreach ($order->details as $detalle)
                            <tr>
                                <td>{{ $detalle->product->name}}</td>
                                <td class="text-right"> $ {{number_format( $detalle->price,0)}}</td>
                                <td class="text-right">{{ $detalle->quantity}}</td>
                                <td class="text-right">$ {{ $detalle->price * $detalle->quantity}}</td><br>

                            </tr> @endforeach
                        </tbody>
                    </table>

                    @endif

                    <br>
                    <div class="card-footer">
                        <div class="card-header">
                            <p class="text-bold"> Historial de Abonos </p>
                        </div>
                        @if($order->abonos->count() > 0)
                        <table id="detalles" class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Código Abono</th>
                                    <th class="text-right">Método de pago</th>
                                    <th class="text-right">Vendedor/a</th>
                                    <th class="text-right">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody> @foreach ($order->abonos as $abono)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($abono->created_at)->format('d M Y') }} </td>
                                    <td class="text-center">{{ $abono->full_nro}}</td>
                                    <td class="text-center">{{ $abono->metodopago }}</td>
                                    <td class="text-center">{{ ucwords($abono->user->name) }}</td>
                                    <td class="text-right"> $ {{number_format( $abono->amount,0)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>

        </div>
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
