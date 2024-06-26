@extends('adminlte::page')

@section('scripts_head')
    @include('includes.head')
@stop

@section('title', 'Dashboard')



@section('content')
    @include('popper::assets')

@if ($role == 'Administrador')
<form method="POST" action="{{ route('update.estadistica') }}">
    @csrf
    <div class="input-group py-2 mb-3 col-4">
        <span class="input-group-text">Mes - Año</span>
        <input type="month" class="form-control" placeholder="mes - año" id="mes_anio" name="mes_anio"
            value="{{ $filter_fecha ?? $fecha_actual }}">
        <button type="submit" class="btn btn-outline-success" title="Recargar Estadísticas"><i
                class="bi bi-arrow-clockwise"></i></button>
    </div>

</form>


<div class="row ">
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

                <p>Cartera</p>
            </div>
            <div class="icon">
                <i class="ion ion-social-usd-outline "></i>
            </div>

        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 ">
        <!-- small box -->
        <div class="small-box bg-teal">
            <div class="inner">
                <h3>$ {{ number_format($recaudo_cartera, 0) }}</h3>

                <p>Recuado Cartera</p>
            </div>
            <div class="icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>

        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 ">
        <!-- small box -->
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>$ {{ number_format($cantidad_consumo, 0) }}</h3>

                <p>Consumo Interno</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>

        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 ">
        <!-- small box -->
        <div class="small-box bg-dark">
            <div class="inner">
                <h3>$ {{ number_format($cantidad_gastos, 0) }}</h3>

                <p>Gastos</p>
            </div>
            <div class="icon">
                <i class="far fa-money-bill-alt"></i>
            </div>

        </div>
    </div>
    <!-- ./col -->
</div>


<div class="row ">
    <div class="col-lg-12">
        <div class="card bg-info" style="height: 350px;">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="">Ingresos de {{\Carbon\Carbon::parse($mes_actual)->locale('es_ES')->translatedFormat(('F'))}} </h3>
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="mt-2">Total ingresos $ {{ number_format($total_ingresos, 0) }} </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-body" style="background-color: #c4eefb; color: black">
                <div class="position-relative mb-4" style="position: relative; width: 100%; height: 100%;">
                    <canvas id="chart"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="card bg-success ">
    <div class="card-header ">
        <h3>Nuestros Mejores Clientes </h3>
    </div>
    <div class="card-body " style="background-color: #d1e7d1; color:black">

        <div class="table-responsive ">
            <table class="table table-success table-striped " id="tabProducts">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Deuda</th>
                        <th>Compra Total</th>
                        <th>Producto Mas Comprado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clientes as $client)
                        <tr>
                            <td>{{ mb_strtoupper($client['cliente']) }} </td>
                            <td>{{ $client['telefono'] }} </td>
                            <td>@if ($client['direccion'])
                                {{ $client['direccion'] }}
                            @else
                               <span class="text-secondary">Sin Dirección</span>
                            @endif  </td>
                            <td>$ {{number_format($client['deuda']) }} </td>
                            <td>$ {{number_format($client['total_compras']) }} </td>
                            <td>{{mb_strtoupper( $client['producto_mas_comprado']) }} </td>

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
{{-- ventas y compras y gastos --}}
<div class="row mt-4" style="height: 250px; ">
    <div class="container-fluid">
        <div class="row">
            <!-- /.col-md-6 -->
            <div class="col-lg-4" width="200" height="200">

                <div class="card card-outline card-success">
                    {{--  Ventas ultimos meses  --}}
                    <canvas id="myChart" style="height: 200px;"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-outline card-primary">
                    {{--  Compras ultimos meses  --}}
                    <canvas id="myChartPurchase" style="height: 200px"></canvas>
                </div>
            </div>

            <div class="col-lg-4" width="200" height="200">

                <div class="card card-outline card-danger">
                    {{--  Ventas ultimos meses  --}}
                    <canvas id="myChartGastos" style="height: 200px;"></canvas>
                </div>
            </div>
        </div>
    </div>
{{-- clientes y gastos --}}




    <div class="row mt-4">

        <div class="col-lg-6">
            <div class="card bg-success">
                <div class="card-header">
                    <h3>Productos más vendidos </h3>
                </div>
                <div class="card-body " style="background-color: #D1E7DD; color:black">

                    <div class="table-responsive ">
                        <table class="table table-success table-striped " id="tabProducts">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th class="text-center">Cantidad Vendida</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topProducts as $product)
                                    <tr>
                                        <td title="{{$product->name}}">{{ substr($product->name, 0, 22) }}... </td>
                                        <td title="{{$product->code}}">{{ substr($product->code, 0, 15) }}... </td>
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
        <div class="col-lg-6">

            <div class="card bg-danger ml-3">
                <div class="card-header ">
                    <h3>Productos menos vendidos </h3>
                </div>
                <div class="card-body " style="background-color: #e7d1d1; color:black">

                    <div class="table-responsive ">
                        <table class="table table-danger table-striped " id="tabProducts">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th class="text-center">Cantidad Vendida</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($MinProducts as $product)
                                    <tr>
                                        <td title="{{$product->name}}">{{ substr($product->name, 0, 22) }}... </td>
                                        <td title="{{$product->code}}">{{ substr($product->code, 0, 15) }}... </td>
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

@else
<div class="row py-3">
    <div class="col-lg-3 col-6 ">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>$ {{ number_format($cantidad_ventas2, 0) }}</h3>

                <p>Ventas  </p>
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

                <p>Compras </p>
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

                <p>Abonos </p>
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

                <p>Cartera</p>
            </div>
            <div class="icon">
                <i class="ion ion-social-usd-outline "></i>
            </div>

        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 ">
        <!-- small box -->
        <div class="small-box bg-teal">
            <div class="inner">
                <h3>$ {{ number_format($recaudo_cartera, 0) }}</h3>

                <p>Recuado Cartera</p>
            </div>
            <div class="icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>

        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 ">
        <!-- small box -->
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>$ {{ number_format($cantidad_consumo, 0) }}</h3>

                <p>Consumo Interno</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>

        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 ">
        <!-- small box -->
        <div class="small-box bg-dark">
            <div class="inner">
                <h3>$ {{ number_format($cantidad_gastos, 0) }}</h3>

                <p>Gastos</p>
            </div>
            <div class="icon">
                <i class="far fa-money-bill-alt"></i>
            </div>

        </div>
    </div>
    <!-- ./col -->
</div>
@endif


    @stop

    @section('css')
        <link rel="stylesheet" href="/css/admin_custom.css">
    @stop

    @section('js')
        <script>
       window.addEventListener('load', resizeCanvas);
window.addEventListener('resize', resizeCanvas);

function resizeCanvas() {
    const canvas = document.getElementById('chart');
    const parent = canvas.parentElement;

    canvas.width = parent.clientWidth;
    canvas.height = parent.clientHeight;

    drawChart(); // Redibuja el gráfico cada vez que se redimensiona el canvas
}

function drawChart() {
    const ctx = document.getElementById('chart').getContext('2d');



    // Crear un nuevo gráfico
    window.myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                @foreach ($data as $item)
                    '{{ $item['day'] }}',
                @endforeach
            ],
            datasets: [{
                    label: 'Ventas',
                    data: [
                        @foreach ($data as $item)
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
                        @foreach ($data as $item)
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
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}

// Llama a resizeCanvas para establecer el tamaño inicial
resizeCanvas();

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

<script>
    var ctx = document.getElementById('myChartGastos').getContext('2d');
    var myChartGastos = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($gastosmonths) !!},
            datasets: [{
                label: 'Gastos',
                data: {!! json_encode($gastostotals) !!},
                backgroundColor: 'rgba(164, 3, 3, 0.2)',
                borderColor: 'rgba(164, 3, 3 )',
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
