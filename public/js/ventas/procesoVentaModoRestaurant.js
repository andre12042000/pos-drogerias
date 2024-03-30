let subTotal = 0;
let iva = 0;
let TOTAL = 0;

let resultadoCalculo = {};

// Asignar los valores a los elementos HTML
document.querySelector(".subTotal").textContent = `${subTotal}`;
document.querySelector(".iva").textContent = `${iva}`;
document.querySelector(".TOTAL").textContent = `${TOTAL}`;

// Obtener los elementos de radio button
const radioPorcentaje = document.getElementById("descuentoPorcentaje");
const radioValorFijo = document.getElementById("descuentoValorFijo");

// Agregar un controlador de eventos para detectar cambios en los elementos de radio button
radioPorcentaje.addEventListener("change", habilitarInputDescuento);
radioValorFijo.addEventListener("change", habilitarInputDescuento);

function habilitarInputDescuento() {
    const totalElement = document.querySelector(".TOTAL");

    // Obtener el valor total como texto del elemento y convertirlo a un número
    const valorTotal = parseFloat(
        totalElement.textContent.replace("$", "").replace(",", "").trim()
    );

    // Redondear el valor total a dos decimales
    const valorTotalRedondeado = valorTotal.toFixed(3);
    const valorTotalEntero = parseInt(valorTotalRedondeado);

    console.log(valorTotalRedondeado);

    /*-----------------------------------------------------------------------------------------




                            ACA VOY CON EL DESCUENTO




        --------------------------------------------------------------------------------------------*/

    // Obtener el valor del tipo de descuento seleccionado
    const tipoDescuento = radioPorcentaje.checked ? "porcentaje" : "valor_fijo";

    // Habilitar el input y establecer el placeholder según el tipo de descuento seleccionado
    inputDescuento.disabled = false;
    inputDescuento.value = ""; // Limpiar el valor actual

    // Restringir el valor máximo según el tipo de descuento
    if (tipoDescuento === "porcentaje") {
        inputDescuento.setAttribute("maxlength", "2"); // Máximo 2 caracteres para descuento porcentaje
        inputDescuento.placeholder = "Descuento en % (máx. 70%)";
        inputDescuento.addEventListener("input", function () {
            if (parseInt(inputDescuento.value) > 70) {
                inputDescuento.value = "70"; // Limitar el valor máximo a 70 si se excede
            }
        });
    } else if (tipoDescuento === "valor_fijo") {
        inputDescuento.placeholder = `Descuento en $ (máx. ${valorTotalRedondeado})`;
        inputDescuento.addEventListener("input", function () {
            if (parseInt(inputDescuento.value) > valorTotalEntero) {
                inputDescuento.value = valorTotalEntero; // Limitar el valor máximo a valorTotalEntero si se excede
            }
        });
    }
}

const pagarBtn = document.getElementById("pagarBtn");

document.getElementById("cuentas_por_mesas").style.display = "none";

// Obtener la cantidad de mesas del localStorage
var cantidadMesas = localStorage.getItem("cantidadMesas");
if (cantidadMesas !== null) {
    // Si la cantidad de mesas está almacenada, crear los cuadros de las mesas
    for (var i = 1; i <= cantidadMesas; i++) {
        // Llamar a la función crearMesa y pasar el número de mesa como parámetro
        crearMesa(i);
    }
}

// Función para crear una mesa y asignar el evento de doble clic
function crearMesa(numMesa) {
    var mesaBox = document.createElement("div");
    mesaBox.className = "mesa-box";
    mesaBox.textContent = "Mesa " + numMesa;
    mesaBox.dataset.numero = numMesa; // Agregar atributo de datos para almacenar el número de la mesa
    document.getElementById("mesas-container").appendChild(mesaBox);

    // Consultar y actualizar el estado de la mesa
    consultarEstadoMesa(numMesa);

    mesaBox.addEventListener("click", function () {
        mostrarVistaMesas();
        filtrarPedidosPorMesa(numMesa);
    });

    mesaBox.addEventListener("dblclick", function () {
        abrirModal(numMesa);
    });
}

function mostrarVistaMesas() {
    document.getElementById("cuentas_por_mesas").style.display = "block";
    document.getElementById("cuentas_mostrador").style.display = "none";
}

function mostrarVistaMostrador() {
    document.getElementById("cuentas_por_mesas").style.display = "none";
    document.getElementById("cuentas_mostrador").style.display = "block";
}

//Verificamos el estado de la mesa segun el pedido del localstorage
function consultarEstadoMesa(numMesa) {
    // Obtener el nombre de la mesa
    let mesaNombre = "Mesa " + numMesa;

    // Obtener los pedidos del localStorage
    let orders = JSON.parse(localStorage.getItem("orders"));

    // Verificar si hay algún pedido para esta mesa
    if (orders !== null) {
        let pedidosMesa = orders.filter((order) => order.mesa === mesaNombre);

        // Obtener la referencia al elemento de la mesa
        let mesa = document.querySelector(
            `.mesa-box[data-numero="${numMesa}"]`
        );

        if (pedidosMesa.length > 0) {
            // Si hay pedidos para esta mesa, marcarla como ocupada
            mesa.dataset.estado = "ocupada";
            actualizarEstadoMesa(mesa); // Actualizar la apariencia de la mesa
        } else {
            // Si no hay pedidos para esta mesa, marcarla como desocupada
            mesa.dataset.estado = "desocupada";
            actualizarEstadoMesa(mesa); // Actualizar la apariencia de la mesa
        }
    }
}

function actualizarEstadoMesa(mesaBox) {
    // Actualizar la apariencia de la mesa según su estado
    if (mesaBox.dataset.estado === "desocupada") {
        mesaBox.style.backgroundColor = "white"; // Color para mesas desocupadas
    } else {
        mesaBox.style.backgroundColor = "#ff9999"; // Color para mesas ocupadas
    }
}

// Función para abrir el modal con el número de la mesa
function abrirModal(numeroMesa) {
    // Definir el texto según si es una mesa o el mostrador
    var tituloModal =
        numeroMesa === "Mostrador" ? "Mostrador" : "Mesa " + numeroMesa;

    // Abrir el modal y establecer el título según corresponda
    $("#numeroMesaModalPedidos").modal("show");
    document.getElementById("solicitantePedido").textContent = tituloModal;
}

document.getElementById("mostrador").addEventListener("dblclick", function () {
    // Llama a la función para abrir el modal, pasando 'Mostrador' como parámetro
});

function filtrarPedidosPorMesa(numMesa) {
    // Obtener los pedidos del localStorage
    let orders = JSON.parse(localStorage.getItem("orders"));

    // Filtrar los pedidos para la mesa seleccionada
    let pedidosMesa = orders.filter(
        (order) => order.mesa === "Mesa " + numMesa
    );

    // Construir la estructura HTML para mostrar los pedidos de la mesa seleccionada
    let cuentasPorMesasHTML = "";

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
                        <h6 class="ml-4"><strong>Pedido Nro. ${
                            index + 1
                        }</strong></h6>
                    </div>
                    <div class="col text-end">
                        <div class="form-check mr-4">
                            <input type="checkbox" class="form-check-input" id="pagar_ronda_mesa_${numMesa}_${
                index + 1
            }" style="cursor:pointer;" onchange="calcularTotalPagar(${numMesa})">
                            <label for="pagar_ronda_mesa_${numMesa}_${
                index + 1
            }"></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <ul class="list-group">
            `;
            pedido.detalles.forEach((item) => {
                // Generar un identificador único para el checkbox de "Pagar Item"
                const uniqueItemId = `pagar_item_${numMesa}_${index + 1}_${
                    item.producto_id
                }`;
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
    document.getElementById("cuentas_por_mesas").innerHTML =
        cuentasPorMesasHTML;
}

/* ------------------------ Proceso para realizar el pago -----------------------
    -------------------------------------------------------------------------------*/

function calcularTotalPagar(numMesa) {
    resultadoCalculo = {};
    // Obtener los pedidos del localStorage
    let orders = JSON.parse(localStorage.getItem("orders"));

    // Filtrar los pedidos para la mesa seleccionada
    let pedidosMesa = orders.filter(
        (order) => order.mesa === "Mesa " + numMesa
    );

    // Inicializar el total a pagar
    let totalPagar = 0;

    // Array para almacenar las tuplas que se pagarán y que deben ser eliminadas del localStorage
    let tuplasAPagar = [];

    // Obtener los checkboxes seleccionados y calcular el total

    // Obtener el estado del checkbox pagar_todo_mesa_
    const pagarTodoCheckbox = document.getElementById(
        `pagar_todo_mesa_${numMesa}`
    );
    const pagarTodo = pagarTodoCheckbox.checked;

    // Obtener los checkboxes seleccionados y calcular el total
    pedidosMesa.forEach((pedido, index) => {
        if (pagarTodo) {
            // Si el cliente decide pagar todo el pedido, agregar el total del pedido al total a pagar
            let totalPedido = 0;
            pedido.detalles.forEach((item) => {
                totalPedido += item.total;
                // Agregar la tupla a pagar al array
                tuplasAPagar.push({
                    mesa: numMesa,
                    pedidoNro: index + 1,
                    producto_id: item.producto_id,
                    cantidad: item.cantidad,
                    precio_unitario: item.precio_unitario,
                    total: item.total,
                    tipo: "pagototal",
                });
            });
            // Agregar el total del pedido al total a pagar
            totalPagar += totalPedido;
        } else {
            // Si el cliente decide pagar solo algunos productos del pedido
            const pagarPedidoCheckbox = document.getElementById(
                `pagar_ronda_mesa_${numMesa}_${index + 1}`
            );
            const pagarPedido = pagarPedidoCheckbox.checked;

            if (pagarPedido) {
                pedido.detalles.forEach((item) => {
                    totalPagar += item.total;
                    // Agregar la tupla a pagar al array
                    tuplasAPagar.push({
                        mesa: numMesa,
                        pedidoNro: index + 1,
                        producto_id: item.producto_id,
                        cantidad: item.cantidad,
                        precio_unitario: item.precio_unitario,
                        total: item.total,
                        tipo: "pagoronda",
                    });
                });
            } else {
                pedido.detalles.forEach((item) => {
                    const pagarItemCheckbox = document.getElementById(
                        `pagar_item_${numMesa}_${index + 1}_${item.producto_id}`
                    );
                    if (pagarItemCheckbox.checked) {
                        totalPagar += item.total;
                        // Agregar la tupla a pagar al array
                        tuplasAPagar.push({
                            mesa: numMesa,
                            pedidoNro: index + 1,
                            producto_id: item.producto_id,
                            cantidad: item.cantidad,
                            precio_unitario: item.precio_unitario,
                            total: item.total,
                            tipo: "pagotupla",
                        });
                    }
                });
            }
        }
    });

    pasarValoresPagoVista(totalPagar, tuplasAPagar);

    return totalPagar;
}

function pasarValoresPagoVista(totalPagar, tuplasAPagar) {
    let iva = 0;
    // Calcular el total (suma del total y el total de IVA)
    const totalConIva = totalPagar + iva;

    // Formatear el total a pagar como moneda colombiana sin decimales
    const formatter = new Intl.NumberFormat("es-CO", {
        style: "currency",
        currency: "COP",
        minimumFractionDigits: 0,
    });
    const totalPagarFormatted = formatter.format(totalPagar);

    // Mostrar los resultados en los elementos HTML
    const subTotalElement = document.querySelector(".subTotal");
    subTotalElement.textContent = totalPagarFormatted; // Mostrar el total a pagar formateado como moneda colombiana sin decimales

    const TotalElement = document.querySelector(".TOTAL");
    TotalElement.textContent = totalPagarFormatted; // Mostrar el total a pagar formateado como moneda colombiana sin decimal

    resultadoCalculo = {
        //Variable Global
        tuplasAPagar: tuplasAPagar,
        totalConIva: totalConIva,
    };

    actualizarEstadoBotonPagar(totalPagar);
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

/*----------------------Proceso de pago -----------------------------------*/

pagarBtn.onclick = function () {
    var tipoOperacionSeleccionado = document.querySelector(
        'input[name="tipoOperacionOptions"]:checked'
    ).value; //Opción seleccionada tipo de operación
    var metodoPagoSeleccionado =
        document.getElementById("selectMetodoPago").value; // Metodo de pago seleccionado
    var imprimirReciboSeleccionado = document.querySelector(
        'input[name="opcionRadio"]:checked'
    ).value;
    var inputDescuentoValor = document.getElementById("inputDescuento").value;

    var ivaSpan = document.querySelector(".iva");
    var totalSpan = document.querySelector(".TOTAL");

    var iva = parseInt(ivaSpan.textContent.replace(/\D/g, ""), 10);
    var total = parseInt(totalSpan.textContent.replace(/\D/g, ""), 10);

    var tipoDescuentoSeleccionado = null;
    var descuentoPorcentajeRadio = document.getElementById(
        "descuentoPorcentaje"
    );
    var descuentoValorFijoRadio = document.getElementById("descuentoValorFijo");

    if (descuentoPorcentajeRadio.checked) {
        tipoDescuentoSeleccionado = descuentoPorcentajeRadio.value;
    } else if (descuentoValorFijoRadio.checked) {
        tipoDescuentoSeleccionado = descuentoValorFijoRadio.value;
    }

    var cliente = document.getElementById("cliente").value;

    Livewire.emit("pagarEvent", {
        resultadoCalculo: resultadoCalculo,
        tipoOperacion: tipoOperacionSeleccionado,
        metodoPago: metodoPagoSeleccionado,
        imprimirRecibo: imprimirReciboSeleccionado,
        subTotal: subTotal,
        tipodescuento: tipoDescuentoSeleccionado,
        descuento: inputDescuentoValor,
        iva: iva,
        total: total,
        cliente_id: cliente,
    });
};

/*----------------------------------------------------------------------------------------

                                Proceso venta mostrador

------------------------------------------------------------------------------------------*/

function mostrarProductosMostrador() {
    // Obtener los datos del local storage
    var orders = JSON.parse(localStorage.getItem("orders")) || [];

    // Filtrar las órdenes que corresponden a la mesa 'MOSTRADOR'
    var mostradorOrders = orders.filter(function (order) {
        return order.mesa === "MOSTRADOR";
    });

    // Obtener la tabla donde se mostrarán los datos
    var table = document.getElementById("productosVentaMostrador");
    var tbody = table.getElementsByTagName("tbody")[0];

    // Limpiar el cuerpo de la tabla antes de agregar nuevas filas
    tbody.innerHTML = "";

    // Iterar sobre cada pedido para la mesa 'MOSTRADOR'
    mostradorOrders.forEach(function (order, index) {
        order.detalles.forEach(function (detalle) {
            // Crear una nueva fila para cada detalle del pedido
            var row = tbody.insertRow();
            var cellNum = row.insertCell(0);
            var cellDesc = row.insertCell(1);
            var cellPrecio = row.insertCell(2);
            var cellCant = row.insertCell(3);
            var cellTotal = row.insertCell(4);
            var cellEliminar = row.insertCell(5);

            // Llenar las celdas con los datos del detalle del pedido
            cellNum.textContent = index + 1;
            cellDesc.textContent = detalle.nombre;
            cellPrecio.textContent = "$" + detalle.precio_unitario.toFixed(0);
            cellCant.textContent = detalle.cantidad;
            cellTotal.textContent =
                "$" + (detalle.cantidad * detalle.precio_unitario).toFixed(0);

            // Crear el botón de eliminar con el ícono de la papelera de reciclaje
            var botonEliminar = document.createElement("button");
            botonEliminar.innerHTML = '<i class="fas fa-trash-alt"></i>'; // Icono de papelera de reciclaje
            botonEliminar.style.border = "none";
            botonEliminar.style.background = "transparent"; // Fondo transparente
            botonEliminar.style.cursor = "pointer"; // Cursor pointer al pasar el ratón

            // Agregar el evento onclick para eliminar el producto del localStorage
            botonEliminar.onclick = function () {
                eliminarPedidoLocalStorage(order.key); // Llama a la función eliminarProducto con el índice del producto
            };

            // Agregar el botón al DOM
            cellEliminar.appendChild(botonEliminar);
        });
    });
}

function calcularTotalMostrador() {
    // Obtener los datos del local storage
    var orders = JSON.parse(localStorage.getItem("orders")) || [];

    // Filtrar las órdenes que corresponden al mostrador
    var mostradorOrders = orders.filter(function (order) {
        return order.mesa === "MOSTRADOR";
    });

    // Inicializar el total a pagar
    var totalPagar = 0;

    let tuplasAPagar = [];

    // Iterar sobre cada pedido para el mostrador y calcular el total
    mostradorOrders.forEach(function (order) {
        order.detalles.forEach(function (detalle) {
            totalPagar += detalle.cantidad * detalle.precio_unitario;

            tuplasAPagar.push({
                mesa: "MOSTRADOR", // Cambiar a 'MOSTRADOR' en lugar de 'numMesa'
                pedidoNro: order.pedidoNro, // Obtener el número de pedido del objeto order
                producto_id: detalle.producto_id,
                cantidad: detalle.cantidad,
                precio_unitario: detalle.precio_unitario,
                total: detalle.cantidad * detalle.precio_unitario,
                tipo: "pagoMOSTRADOR",
            });
        });
    });

    pasarValoresPagoVista(totalPagar, tuplasAPagar);

    // Retornar el total calculado
    return totalPagar;
}

document.addEventListener("DOMContentLoaded", function () {
    mostrarProductosMostrador();
});

/*------------------------------Evento para recibir empezar proceso pago mostrador ---------------------------*/
// Obtener el checkbox
var checkboxPagarMostrador = document.getElementById("pagarMostrador");

// Agregar un evento onchange para detectar cuando cambia el estado del checkbox
checkboxPagarMostrador.addEventListener("change", function () {
    // Verificar si el checkbox está seleccionado
    if (checkboxPagarMostrador.checked) {
        calcularTotalMostrador();
    } else {
        // El checkbox está deseleccionado
        // Aquí puedes ejecutar las acciones que deseas realizar cuando el checkbox se deselecciona
        console.log("El checkbox está deseleccionado");
    }
});

/*-----------------------Alert despues de guardado --------------------------------------------*/

window.addEventListener("venta-generada", (event) => {
    const numeroVenta = event.detail.venta;
    const tuplasAEliminar = event.detail.tuplas;

    tuplasAEliminar.forEach((tupla) => {
        let mesa = tupla.mesa;
        let pedido = tupla.pedidoNro;
        let producto = tupla.producto_id;
        // Suponiendo que la clave única está en la propiedad 'key'

        if (mesa === "MOSTRADOR") {
            eliminarPedidoLocalStorageMostrador();
        } else {
            eliminarPedidoLocalStorageMesa(mesa, pedido, producto);
        }
    });

     Swal.fire({
        icon: "success",
        title: "Venta realizada correctamente",
        text: `Número de venta: ${numeroVenta}`,
        showConfirmButton: false,
        timer: 1500
    });
    setTimeout(() => {
        location.reload();
    }, 1500);
});

function eliminarPedidoLocalStorageMesa(mesa, pedidoNro, producto_id) {
    var mesa = "Mesa" + " " + mesa;
    var pedido = "Pedido Nro:" + " " + pedido;

    let orders = JSON.parse(localStorage.getItem("orders")) || [];

    // Filtramos las ordenes de la mesa
    let ordenesDeMesa = orders.filter((order) => order.mesa === mesa);

    let ordenEncontrada = ordenesDeMesa.find(
        (order) => order.pedidoNro === pedidoNro
    );

    if (ordenEncontrada) {
        let detalles = ordenEncontrada.detalles;

        // Recorrer los detalles para buscar el producto_id
        detalles.forEach((detalle, index) => {
            if (detalle.producto_id === producto_id) {
                // Paso 6: Eliminar el detalle si se encontró
                detalles.splice(index, 1);

                // Paso 7: Actualizar los datos en el localStorage
                localStorage.setItem("orders", JSON.stringify(orders));

                if (detalles.length === 0) {
                    // Eliminar el pedido si no tiene detalles
                    orders.splice(orders.indexOf(ordenEncontrada), 1);
                    localStorage.setItem("orders", JSON.stringify(orders));
                    console.log("Pedido eliminado porque ya no tiene detalles");
                }

                return; // Salir del bucle una vez que se elimine el detalle
            }
        });
    }
}

function eliminarPedidoLocalStorageMostrador() {
    // Obtener los pedidos del localStorage
    let orders = JSON.parse(localStorage.getItem("orders")) || [];

    let mostradorOrders = orders.filter((order) => order.mesa === "MOSTRADOR");

    // Paso 3: Eliminar los pedidos filtrados del array de datos
    mostradorOrders.forEach((order) => {
        // Encuentra el índice del pedido en el array de datos
        let index = orders.findIndex((item) => item === order);
        // Elimina el pedido del array
        orders.splice(index, 1);
    });

    // Paso 4: Actualizar el localStorage con los datos filtrados
    localStorage.setItem("orders", JSON.stringify(orders));
}
