@extends('adminlte::page')

@section('scripts_head')
@include('includes.head')
@stop

@section('title', 'Dashboard')



@section('content')
@include('popper::assets')


<div class="row mt-1">
    <div class="col-lg-3 col-6 ">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>$ {{ number_format($cantidad_ventas, 0) }}</h3>

                <p>Ventas Mensuales </p>
            </div>
            <div class="icon">
                <i class="las la-cash-register "></i>
            </div>

        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-lightblue">
            <div class="inner">
                <h3>$ {{ number_format($cantidad_compras, 0) }}</h3>

                <p>Compras Mensuales</p>
            </div>
            <div class="icon">
                <i class="ion ion-arrow-graph-up-right "></i>
            </div>

        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>$ {{ number_format($cantidad_abonos, 0) }}</h3>

                <p>Abonos Mensuales</p>
            </div>
            <div class="icon">
            <i class="las la-donate"></i>
            </div>

        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>$ {{ number_format($cantidad_deuda, 0) }}</h3>

                <p>Deudas Actuales</p>
            </div>
            <div class="icon">
                <i class="ion ion-social-usd-outline "></i>
            </div>

        </div>
    </div>
    <!-- ./col -->
</div>


<div class="row mt-4" >
            <div class="col-lg-12">
                <div class="card card-info" style="height: 350px;">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                            <h3 class="">Ingresos de {{ $mes_actual }} </h3>
                            <div class="d-flex">
                            <p class="d-flex flex-column">
                                <span class="mt-2">Total ingresos $ {{ number_format($total_ingresos, 0) }} </span>
                            </p>
                        </div>
                        </div>
                    </div>
                    <div class="card-body" >
                        <div class="position-relative mb-4">
                            {{-- <canvas id="salesChart"  style="height: 180px;"></canvas> --}}
                            <canvas id="chart"  style="height: 500px;" height="180px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
</div>

<div class="row mt-4" style="height: 250px; ">
    <div class="container-fluid">
        <div class="row">
            <!-- /.col-md-6 -->
            <div class="col-lg-6" width="200" height="200">

                <div class="card card-outline card-success">
                   {{--  Ventas ultimos meses  --}}
                    <canvas id="myChart" style="height: 250px;"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-outline card-primary">
                   {{--  Compras ultimos meses  --}}
                    <canvas id="myChartPurchase"  style="height: 250px"></canvas>
                </div>
            </div>
        </div>
    </div>



<div class="row mt-4">
    <div class="container-fluid">
        <div class="row">
            <!-- /.col-md-6 -->
            <div class="col-lg-12">
                <div class="card card-info">
                    <div class="card-header card-outline-success">
                        <div class="d-flex justify-content-between">
                            <h3>Productos más vendidos </h3>

                        </div>
                    </div>
                    <div class="card-body " style="background-color: #D1E7DD;">

                            <div class="table-responsive col-md-12">
                                <table class="table table-success table-striped " id="tabProducts" >
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Código</th>
                                            <th class="text-center">Stock</th>
                                            <th class="text-center">Cantidad Vendida</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                         @forelse ($topProducts as $product)
                                         <tr>
                                            <td>{{ $product->name }} </td>
                                            <td>{{ $product->code }} </td>
                                            <td class="text-center">{{ $product->stock }} </td>
                                            <td class="text-center">{{ $product->total_quantity }} </td>
                                         </tr>
                                         @empty
                                         <tr>
                                             <td colspan="10">
                                                 <p>No se encontraron registros...</p>
                                             </td>
                                         </tr>

                                         @endforelse
                                    </tbody>
                                </table>
                            </div>

                    </div>
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
    var ctx = document.getElementById('chart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($data as $item)
                    '{{ $item["day"] }}',
                @endforeach
            ],
            datasets: [
                {
                    label: 'Ventas',
                    data: [
                        @foreach($data as $item)
                            {{ $item['ventas'] }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(41, 205, 25, 0.5)',
                    borderColor: 'rgba(41, 205, 25, 1)',
                    borderWidth: 1,


                },
                {
                    label: 'Abonos',
                    data: [
                        @foreach($data as $item)
                            {{ $item['abonos'] }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(65, 70, 75, 0.5)',
                    borderColor: 'rgba(65, 70, 75, 1)',
                    borderWidth: 1,


                }

            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Ventas',
                data: {!! json_encode($totals) !!},
                backgroundColor: 'rgba(41, 205, 25, 0.5)',
                    borderColor: 'rgba(41, 205, 25, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    </script>

<script>
    var ctx = document.getElementById('myChartPurchase').getContext('2d');
    var myChartPurchase = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($purchasemonths) !!},
            datasets: [{
                label: 'Compras',
                data: {!! json_encode($purchasetotals) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    </script>


@stop
