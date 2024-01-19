<div>
@section('scripts_head')
    @include('includes.head')
@stop
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="chart"></canvas>

<script>
    var ventas = @json($ventas);
    var compras = @json($compras);

    var ctx = document.getElementById('chart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($meses) !!},
            datasets: [{
                label: 'Ventas',
                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                borderColor: 'rgba(0, 123, 255, 1)',
                data: ventas,
                fill: false
            }, {
                label: 'Compras',
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                data: compras,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</div>
