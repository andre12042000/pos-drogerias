<div>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-7">

                <div class="card" id="cuentas_mostrador" style="height: 550px;">
                    MOSTRADOR
                    {{-- Contenido dinámico --}}
                </div>

                <div class="card" id="cuentas_por_mesas" style="height: 550px;">
                    {{-- Contenido dinámico --}}
                </div>

            </div>
            <div class="col-md-5">
                <div class="card" id="componente_pago" style="height: 550px;">
                    @include('livewire.sale.subforms.pagos')
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
@include('modals.client.search')
@include('modals.client.create')


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
    let subTotal = 0;
    let iva = 0;
    let TOTAL = 0;

    // Asignar los valores a los elementos HTML
    document.querySelector('.subTotal').textContent = `${subTotal}`;
    document.querySelector('.iva').textContent = `${iva}`;
    document.querySelector('.TOTAL').textContent = `${TOTAL}`;

    // Obtener los elementos de radio button
    const radioPorcentaje = document.getElementById('descuentoPorcentaje');
    const radioValorFijo = document.getElementById('descuentoValorFijo');



    // Agregar un controlador de eventos para detectar cambios en los elementos de radio button
    radioPorcentaje.addEventListener('change', habilitarInputDescuento);
    radioValorFijo.addEventListener('change', habilitarInputDescuento);

    function habilitarInputDescuento() {

        const totalElement = document.querySelector('.TOTAL');

        // Obtener el valor total como texto del elemento y convertirlo a un número
        const valorTotal = parseFloat(totalElement.textContent.replace('$', '').replace(',', '').trim());

        // Redondear el valor total a dos decimales
        const valorTotalRedondeado = valorTotal.toFixed(3);
        const valorTotalEntero = parseInt(valorTotalRedondeado);

        console.log(valorTotalRedondeado);

        /*-----------------------------------------------------------------------------------------




                            ACA VOY CON EL DESCUENTO




        --------------------------------------------------------------------------------------------*/



        // Obtener el valor del tipo de descuento seleccionado
        const tipoDescuento = (radioPorcentaje.checked) ? 'porcentaje' : 'valor_fijo';

        // Habilitar el input y establecer el placeholder según el tipo de descuento seleccionado
        inputDescuento.disabled = false;
        inputDescuento.value = ''; // Limpiar el valor actual

        // Restringir el valor máximo según el tipo de descuento
        if (tipoDescuento === 'porcentaje') {
            inputDescuento.setAttribute('maxlength', '2'); // Máximo 2 caracteres para descuento porcentaje
            inputDescuento.placeholder = 'Descuento en % (máx. 70%)';
            inputDescuento.addEventListener('input', function() {
                if (parseInt(inputDescuento.value) > 70) {
                    inputDescuento.value = '70'; // Limitar el valor máximo a 70 si se excede
                }
            });
        } else if (tipoDescuento === 'valor_fijo') {
            inputDescuento.placeholder = `Descuento en $ (máx. ${valorTotalRedondeado})`;
            inputDescuento.addEventListener('input', function() {
                if (parseInt(inputDescuento.value) > valorTotalEntero) {
                    inputDescuento.value = valorTotalEntero; // Limitar el valor máximo a valorTotalEntero si se excede
                }
            });
        }

    }



    const pagarBtn = document.getElementById('pagarBtn');

    document.getElementById('cuentas_por_mesas').style.display = 'none';

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
        mesaBox.dataset.numero = numMesa; // Agregar atributo de datos para almacenar el número de la mesa
        document.getElementById('mesas-container').appendChild(mesaBox);

        // Consultar y actualizar el estado de la mesa
        consultarEstadoMesa(numMesa);

        mesaBox.addEventListener('click', function() {
            mostrarVistaMesas();
            filtrarPedidosPorMesa(numMesa);
        });

        mesaBox.addEventListener('dblclick', function() {
            abrirModal(numMesa);
        });
    }

    function mostrarVistaMesas() {
        document.getElementById('cuentas_por_mesas').style.display = 'block';
        document.getElementById('cuentas_mostrador').style.display = 'none';
    }

    function mostrarVistaMostrador() {
        document.getElementById('cuentas_por_mesas').style.display = 'none';
        document.getElementById('cuentas_mostrador').style.display = 'block';
    }

    //Verificamos el estado de la mesa segun el pedido del localstorage
    function consultarEstadoMesa(numMesa) {
        // Obtener el nombre de la mesa
        let mesaNombre = 'Mesa ' + numMesa;

        // Obtener los pedidos del localStorage
        let orders = JSON.parse(localStorage.getItem('orders'));

        // Verificar si hay algún pedido para esta mesa
        if (orders !== null) {
            let pedidosMesa = orders.filter(order => order.mesa === mesaNombre);

            // Obtener la referencia al elemento de la mesa
            let mesa = document.querySelector(`.mesa-box[data-numero="${numMesa}"]`);

            if (pedidosMesa.length > 0) {
                // Si hay pedidos para esta mesa, marcarla como ocupada
                mesa.dataset.estado = 'ocupada';
                actualizarEstadoMesa(mesa); // Actualizar la apariencia de la mesa
            } else {
                // Si no hay pedidos para esta mesa, marcarla como desocupada
                mesa.dataset.estado = 'desocupada';
                actualizarEstadoMesa(mesa); // Actualizar la apariencia de la mesa
            }
        }
    }

    function actualizarEstadoMesa(mesaBox) {
        // Actualizar la apariencia de la mesa según su estado
        if (mesaBox.dataset.estado === 'desocupada') {
            mesaBox.style.backgroundColor = 'white'; // Color para mesas desocupadas
        } else {
            mesaBox.style.backgroundColor = '#ff9999'; // Color para mesas ocupadas
        }
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

    });

    function filtrarPedidosPorMesa(numMesa) {
        // Obtener los pedidos del localStorage
        let orders = JSON.parse(localStorage.getItem('orders'));

        // Filtrar los pedidos para la mesa seleccionada
        let pedidosMesa = orders.filter(order => order.mesa === 'Mesa ' + numMesa);

        // Construir la estructura HTML para mostrar los pedidos de la mesa seleccionada
        let cuentasPorMesasHTML = '';

        // Agregar el título con el número de la mesa y el checkbox para pagar todo al final
        cuentasPorMesasHTML += `
        <div class="row m-2">
            <div class="col">
                <h5>Mesa ${numMesa}</h5>
            </div>
            <div class="col text-end">
                <div class="form-check mr-4">
                    <input type="checkbox" class="form-check-input" id="pagar_todo_mesa_${numMesa}" style="cursor:pointer;" onchange="calcularTotalPagar(${numMesa})">
                    <label for="pagar_todo_mesa_${numMesa}">Pagar</label>
                </div>
            </div>
        </div>
    `;

        // Mostrar un mensaje si no hay pedidos para la mesa seleccionada
        if (pedidosMesa.length === 0) {
            cuentasPorMesasHTML += `
            <div class="col-md-12">
                <p>No hay pedidos para esta mesa.</p>
            </div>
        `;
        } else {
            // Mostrar los pedidos de la mesa seleccionada
            pedidosMesa.forEach((pedido, index) => {
                cuentasPorMesasHTML += `
                <div class="row m-2">
                    <div class="col">
                        <h6 class="ml-4"><strong>Pedido Nro. ${index + 1}</strong></h6>
                    </div>
                    <div class="col text-end">
                        <div class="form-check mr-4">
                            <input type="checkbox" class="form-check-input" id="pagar_ronda_mesa_${numMesa}_${index + 1}" style="cursor:pointer;" onchange="calcularTotalPagar(${numMesa})">
                            <label for="pagar_ronda_mesa_${numMesa}_${index + 1}"></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <ul class="list-group">
            `;
                pedido.detalles.forEach(item => {
                    // Generar un identificador único para el checkbox de "Pagar Item"
                    const uniqueItemId = `pagar_item_${numMesa}_${index + 1}_${item.producto_id}`;
                    cuentasPorMesasHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div><input class="form-check-input ml-2" type="checkbox" value="" id="${uniqueItemId}" style="margin-top: -6px;" onchange="calcularTotalPagar(${numMesa})"></div>
                        <div style="width: 30%;">${item.nombre}</div>
                        <div style="width: 10%;">Cant. ${item.cantidad}</div>
                        <div style="width: 20%;">Precio Unit: $${item.precio_unitario}</div>
                        <div style="width: 20%;">SubTotal: $${item.total}</div>
                    </li>
                `;
                });
                cuentasPorMesasHTML += `
                    </ul>
                </div>
            `;
            });
        }

        // Mostrar los pedidos de la mesa seleccionada en el div cuentas_por_mesas
        document.getElementById('cuentas_por_mesas').innerHTML = cuentasPorMesasHTML;
    }


    /* ------------------------ Proceso para realizar el pago -----------------------
    -------------------------------------------------------------------------------*/


    function calcularTotalPagar(numMesa) {


        // Obtener los pedidos del localStorage
        let orders = JSON.parse(localStorage.getItem('orders'));

        // Filtrar los pedidos para la mesa seleccionada
        let pedidosMesa = orders.filter(order => order.mesa === 'Mesa ' + numMesa);

        // console.log(pedidosMesa);

        // Inicializar el total a pagar
        let totalPagar = 0;

        // Array para almacenar las tuplas que se pagarán y que deben ser eliminadas del localStorage
        let tuplasAPagar = [];

        // Obtener los checkboxes seleccionados y calcular el total

        // Obtener el estado del checkbox pagar_todo_mesa_
        const pagarTodoCheckbox = document.getElementById(`pagar_todo_mesa_${numMesa}`);
        const pagarTodo = pagarTodoCheckbox.checked;

        // Obtener los checkboxes seleccionados y calcular el total
        pedidosMesa.forEach((pedido, index) => {
            if (pagarTodo) {
                // Si el cliente decide pagar todo el pedido, agregar el total del pedido al total a pagar
                let totalPedido = 0;
                pedido.detalles.forEach(item => {
                    totalPedido += item.total;
                    // Agregar la tupla a pagar al array
                    tuplasAPagar.push({
                        mesa: numMesa,
                        pedidoNro: index + 1,
                        producto_id: item.producto_id
                    });
                });
                // Agregar el total del pedido al total a pagar
                totalPagar += totalPedido;
            } else {
                // Si el cliente decide pagar solo algunos productos del pedido
                const pagarPedidoCheckbox = document.getElementById(`pagar_ronda_mesa_${numMesa}_${index + 1}`);
                const pagarPedido = pagarPedidoCheckbox.checked;

                if (pagarPedido) {
                    pedido.detalles.forEach(item => {
                        totalPagar += item.total;
                        // Agregar la tupla a pagar al array
                        tuplasAPagar.push({
                            mesa: numMesa,
                            pedidoNro: index + 1,
                            producto_id: item.producto_id
                        });
                    });
                } else {
                    pedido.detalles.forEach(item => {
                        const pagarItemCheckbox = document.getElementById(
                            `pagar_item_${numMesa}_${index + 1}_${item.producto_id}`);
                        if (pagarItemCheckbox.checked) {
                            totalPagar += item.total;
                            // Agregar la tupla a pagar al array
                            tuplasAPagar.push({
                                mesa: numMesa,
                                pedidoNro: index + 1,
                                producto_id: item.producto_id
                            });
                        }
                    });
                }
            }
        });

        let iva = 0;
        // Calcular el total (suma del total y el total de IVA)
        const totalConIva = totalPagar + iva;

        // Formatear el total a pagar como moneda colombiana sin decimales
        const formatter = new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0
        });
        const totalPagarFormatted = formatter.format(totalPagar);

        // Mostrar los resultados en los elementos HTML
        const subTotalElement = document.querySelector('.subTotal');
        subTotalElement.textContent =
            totalPagarFormatted; // Mostrar el total a pagar formateado como moneda colombiana sin decimales

        const TotalElement = document.querySelector('.TOTAL');
        TotalElement.textContent =
            totalPagarFormatted; // Mostrar el total a pagar formateado como moneda colombiana sin decimal

        actualizarEstadoBotonPagar(totalPagar);

        return totalPagar;
    }

    function actualizarEstadoBotonPagar(totalPagar) {
        // Verificar si el total a pagar es mayor que 0
        if (totalPagar > 0) {
            // Si el total a pagar es mayor que 0, habilitar el botón de pagar
            pagarBtn.disabled = false;
        } else {
            // Si el total a pagar es 0 o menor, deshabilitar el botón de pagar
            pagarBtn.disabled = true;
        }
    }

    /*--------------------Calcular Descuentos ---------------------------------*/
</script>
