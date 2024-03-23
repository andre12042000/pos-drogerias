<div>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-7">
                <div class="card" id="cuentas_por_mesas">
                   {{--  @include('livewire.sale.subforms.productos_mesas') --}}
                </div>
            </div>
            <div class="col-md-5">
                <div class="card" id="listado_productos" style="height: 500px;">

                </div>
            </div>
        </div>
        <div class="row row-expand">
            <div class="col-md-12">
                <div class="card" id="mesas" style="height: 200px;">
                    @include('livewire.sale.subforms.mesas')
                    <span class="position-absolute top-0 end-0 m-2">
                        <i class="fas fa-cog" style="cursor: pointer" data-bs-toggle="modal"
                            data-bs-target="#cantidadMesasModal"></i>
                    </span>

                </div>
            </div>
        </div>
    </div>
    @include('modals.sale.pedidos')
</div>
@include('modals.sale.editarcantidadmesas')


<style>
    .container-fluid {
        display: flex;
        flex-direction: column;
        height: 100vh;
        /* Altura total de la ventana */
    }

    .row-expand {
        flex-grow: 1;
        /* Hace que este div crezca y ocupe todo el espacio restante */
    }

    .bottom-section {
        display: flex;
        justify-content: space-around;
        align-items: center;
        background-color: #f0f0f0;
        padding: 20px;
    }

    .counter,
    .tables {
        text-align: center;
    }

    .counter {
        width: 200px;
        /* Ancho del mostrador */
        height: 150px;
        /* Altura del mostrador */
        background-color: #e0e0e0;
        /* Color de fondo del mostrador */
        border-radius: 15px;
        /* Radio de los bordes redondeados */
        text-align: center;
        padding: 20px;
    }

    .tables {
        flex: 3;
        /* Ocupa tres veces más espacio que el mostrador */
    }

    .counter h3,
    .tables h3 {
        margin-bottom: 10px;
    }

    /* Estilos para las mesas */
    .tables .table {
        width: 100px;
        height: 100px;
        background-color: #ffffff;
        border: 1px solid #000000;
        border-radius: 50%;
        margin-bottom: 10px;
    }

    .mesa-box {
        width: 100px;
        height: 100px;
        background-color: #ffffff;
        border: 1px solid #000000;
        border-radius: 15px;
        margin: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
</style>


<script>
    // Script para recuperar o establecer la cantidad de mesas en localStorage
    var cantidadMesas = localStorage.getItem('cantidadMesas');

    if (cantidadMesas === null) {
        cantidadMesas = 20; // Cantidad predeterminada de mesas
        localStorage.setItem('cantidadMesas', cantidadMesas);
    }
</script>

<script>
    // Obtener la cantidad de mesas del localStorage
    var cantidadMesas = localStorage.getItem('cantidadMesas');
    if (cantidadMesas !== null) {
        // Si la cantidad de mesas está almacenada, crear los cuadros de las mesas
        for (var i = 1; i <= cantidadMesas; i++) {
            // Llamar a la función crearMesa y pasar el número de mesa como parámetro
            crearMesa(i);
        }
    }

    // Función para crear una mesa y asignar el evento de doble clic
    function crearMesa(numMesa) {
    var mesaBox = document.createElement('div');
    mesaBox.className = 'mesa-box';
    mesaBox.textContent = 'Mesa ' + numMesa;
    document.getElementById('mesas-container').appendChild(mesaBox);

    mesaBox.addEventListener('click', function() {
        filtrarPedidosPorMesa(numMesa);
    });

    mesaBox.addEventListener('dblclick', function() {
        abrirModal(numMesa);
    });
}


    // Función para abrir el modal con el número de la mesa
    function abrirModal(numeroMesa) {
        // Definir el texto según si es una mesa o el mostrador
        var tituloModal = numeroMesa === 'Mostrador' ? 'Mostrador' : 'Mesa ' + numeroMesa;

        // Abrir el modal y establecer el título según corresponda
        $('#numeroMesaModalPedidos').modal('show');
        document.getElementById('solicitantePedido').textContent = tituloModal;
    }

    document.getElementById('mostrador').addEventListener('dblclick', function() {
            // Llama a la función para abrir el modal, pasando 'Mostrador' como parámetro
            abrirModal('Mostrador');
    });

    function filtrarPedidosPorMesa(numMesa) {
        console.log('olis');
    // Obtener los pedidos del localStorage
    let orders = JSON.parse(localStorage.getItem('orders'));

    // Filtrar los pedidos para la mesa seleccionada
    let pedidosMesa = orders.filter(order => order.mesa === 'Mesa ' + numMesa);

    // Construir la estructura HTML para mostrar los pedidos de la mesa seleccionada
    let cuentasPorMesasHTML = '';
    pedidosMesa.forEach((pedido, index) => {
        cuentasPorMesasHTML += `
            <div class="col-md-12">
                <h6><strong>Pedido Nro. ${index + 1}</strong></h6>
                <ul class="list-group">
        `;
        pedido.detalles.forEach(item => {
            cuentasPorMesasHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div><input class="form-check-input ml-2" type="checkbox" value="" id="flexCheckDefault" style="margin-top: -6px;"></div>
                    <div style="width: 25%;">${item.producto_id}</div>
                    <div style="width: 16%;">Cant. ${item.cantidad}</div>
                    <div style="width: 20%;">Precio Unit. $${item.precio_unitario}</div>
                    <div style="width: 17%;">Subtotal $${item.total}</div>
                </li>
            `;
        });
        cuentasPorMesasHTML += `
                </ul>
            </div>
        `;
    });

    // Mostrar los pedidos de la mesa seleccionada en el div cuentas_por_mesas
    document.getElementById('cuentas_por_mesas').innerHTML = cuentasPorMesasHTML;
}

</script>
