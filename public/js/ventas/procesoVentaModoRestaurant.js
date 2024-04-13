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

document.addEventListener("DOMContentLoaded", function() {
    // Asignar evento de clic al primer botón
    var botonCerrar1 = document.getElementById("botonCerrar1");
    if (botonCerrar1) {
        botonCerrar1.addEventListener("click", function() {
            cerrarModal();
        });
    }

    // Asignar evento de clic al segundo botón
    var botonCerrar2 = document.getElementById("botonCerrar2");
    if (botonCerrar2) {
        botonCerrar2.addEventListener("click", function() {
            cerrarModal();
        });
    }
});

function habilitarInputDescuento() {
    const totalElement = document.querySelector(".subTotal");

    // Obtener el valor total como texto del elemento y convertirlo a un número
    const valorTotal = parseFloat(
        totalElement.textContent.replace("$", "").replace(",", "").trim()
    );

    const valorTotalRedondeado = valorTotal.toFixed(3);

    let numeroString = valorTotalRedondeado;
    // Eliminar el punto decimal
    let numeroSinPunto = numeroString.replace(".", "");
    // Convertir la cadena resultante en un entero
    let numeroEntero = parseInt(numeroSinPunto);

    // Obtener el valor del tipo de descuento seleccionado
    const tipoDescuento = radioPorcentaje.checked ? "porcentaje" : "valor_fijo";

    inputDescuento.disabled = false;
    inputDescuento.value = ""; // Limpiar el valor actual

    // Restringir el valor máximo según el tipo de descuento
    if (tipoDescuento === "porcentaje") {
        inputDescuento.setAttribute("maxlength", "2"); // Máximo 2 caracteres para descuento porcentaje
        inputDescuento.placeholder = "% (máx. 70%)";
        inputDescuento.addEventListener("input", function () {
            if (parseInt(inputDescuento.value) > 70) {
                inputDescuento.value = "70"; // Limitar el valor máximo a 70 si se excede
            }
        });
    } else if (tipoDescuento === "valor_fijo") {
        inputDescuento.placeholder = `$ (máx. ${valorTotalRedondeado})`;
        inputDescuento.addEventListener("input", function () {
            if (parseInt(inputDescuento.value) > numeroEntero) {
                inputDescuento.value = numeroEntero; // Limitar el valor máximo a valorTotalEntero si se excede
            }
        });
    }
}

inputDescuento.addEventListener("input", function () {
    const tipoDescuento = radioPorcentaje.checked ? "porcentaje" : "valor_fijo";
    let descuento = inputDescuento.value;

    const subTotalElement = document.querySelector(".subTotal");

    const valorSubTotal = parseFloat(
        subTotalElement.textContent.replace("$", "").replace(",", "").trim()
    );

    const valorSubTotalRedondeado = valorSubTotal.toFixed(3);

    let subTotalString = valorSubTotalRedondeado;
    // Eliminar el punto decimal
    let subTotalSinPunto = subTotalString.replace(".", "");
    // Convertir la cadena resultante en un entero
    let subTotalEntero = parseInt(subTotalSinPunto); //Disponible para operar el subtotal

    // Ahora convetimos el iva desde el span en integer

    const ivaElement = document.querySelector(".iva");

    const valorIva = parseFloat(
        ivaElement.textContent.replace("$", "").replace(",", "").trim()
    );

    const valorIvaRedondeado = valorIva.toFixed(3);

    let ivaString = valorIvaRedondeado;
    // Eliminar el punto decimal
    let ivaSinPunto = ivaString.replace(".", "");
    // Convertir la cadena resultante en un entero
    let ivaEntero = parseInt(ivaSinPunto); //Disponible para operar el subtotal

    let precioOriginal = subTotalEntero + ivaEntero;

    let nuevo_total;

    if (tipoDescuento === "porcentaje") {
        // Calcular el descuento basado en el porcentaje
        let porcentajeDescuento = descuento / 100;
        let valorDescuento = precioOriginal * porcentajeDescuento;
        nuevo_total = precioOriginal - valorDescuento;
    } else if (tipoDescuento === "valor_fijo") {
        // Aplicar el descuento directamente si es valor fijo
        nuevo_total = precioOriginal - descuento;
    }

    const totalElement = document.querySelector(".TOTAL");
    totalElement.textContent = "$" + nuevo_total.toFixed(0);
});

const pagarBtn = document.getElementById("pagarBtn");
const descuentoBtn = document.getElementById("descuentoBtn");

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

function mostrarEtiquetaPorMesa(mesa) {
    // Obtener los pedidos del localStorage
    let orders = JSON.parse(localStorage.getItem("orders")) || [];

    let mesaFiltrada = orders.find((item) => item.mesa === "Mesa " + mesa);

    if (mesaFiltrada) {
        return mesaFiltrada.etiqueta;
    } else {
        return null;
    }
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
        mesaBox.style.backgroundColor = "#E1F0DA"; // Color para mesas desocupadas
    } else {
        mesaBox.style.backgroundColor = "#FC6736"; // Color para mesas ocupadas
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

    var inputEtiqueta = document.getElementById("etiqueta");
    // Limpiar el valor del input
    inputEtiqueta.value = "";

    let etiqueta = mostrarEtiquetaPorMesa(numeroMesa);
    if (etiqueta) {
        $("#etiqueta").val(etiqueta);
    }
}

function cerrarModal() {
    // Cerrar el modal
    $("#numeroMesaModalPedidos").modal("hide");

    // Limpiar las variables u opciones seleccionadas
    $("#etiqueta").val(""); // Limpiar el valor del input de etiqueta

    // Si hay otras variables u opciones que necesiten limpiarse, puedes hacerlo aquí
}

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
                    <input type="checkbox" class="form-check-input" id="pagar_todo_mesa_${numMesa}" style="cursor:pointer;" value="PagarTodo_${numMesa}" onchange="calcularTotalPagar(${numMesa}, 'todo')">
                    <label for="pagar_todo_mesa_${numMesa}">Pagar Todo</label>
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
        pedidosMesa.forEach((pedido, pedidoIndex) => {
            const pedidoId = `pedido_${numMesa}_${pedidoIndex}`; // Identificador único para el pedido
            cuentasPorMesasHTML += `
                <div class="row m-2">
                    <div class="col">
                        <h6 class="ml-4"><strong>Pedido Nro. ${
                            pedido.pedidoNro
                        }</strong>
                        <span class="badge bg-secondary ml-4"> ${
                          pedido.etiqueta } </span></h6>
                    </div>
                    <div class="col text-end">
                        <div class="form-check mr-4">
                            <input type="checkbox" class="form-check-input" id="pagar_ronda_mesa_${numMesa}_${pedidoIndex}" style="cursor:pointer;" value="${
                pedido.key
            }" onchange="calcularTotalPagar(${numMesa}, 'pedido', '${
                pedido.key
            }')">
                            <label for="pagar_ronda_mesa_${numMesa}_${pedidoIndex}">Pagar Pedido</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <ul class="list-group mb-4" id="${pedidoId}"> <!-- Usar el pedidoId como ID del UL -->
            `;
            pedido.detalles.forEach((item, itemIndex) => {
                // Generar un identificador único para cada ítem
                const uniqueItemId = `item_${numMesa}_${pedidoIndex}_${itemIndex}`;
                cuentasPorMesasHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-center" id="${uniqueItemId}"> <!-- Usar uniqueItemId como ID del LI -->
                        <div><input class="form-check-input ml-2" type="checkbox" value="${item.key}" id="${uniqueItemId}_checkbox" style="margin-top: -6px;" onchange="calcularTotalPagar(${numMesa}, 'item', '${item.key}')"></div>
                        <div style="width: 30%;">${item.nombre}</div>
                        <div style="width: 10%;">Cant. ${item.cantidad}</div>
                        <div style="width: 20%;">Precio Unit: $${item.precio_unitario}</div>
                        <div style="width: 20%;">SubTotal: $${item.total}</div>
                        <div>
                            <i class="fa fa-trash" style="cursor: pointer;" onclick="eliminarLocalStoragePagoItem(${numMesa}, '${item.key}')"></i>
                        </div>
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

function eliminarItemMesa(mesa, pedido, producto) {
    // Mostrar el SweetAlert de confirmación
    Swal.fire({
        title: "¿Está seguro?",
        text: "Esta acción eliminará el registro. ¿Está seguro que desea continuar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        // Si el usuario confirma la eliminación
        if (result.isConfirmed) {
            // Llamar a la función para eliminar el pedido del local storage
            eliminarPedidoLocalStorageMesa(mesa, pedido, producto);

            // Actualizar la vista filtrando los pedidos por mesa
            filtrarPedidosPorMesa(mesa);

            // Consultar el estado de la mesa
            consultarEstadoMesa(mesa);
        }
    });
}

/* ------------------------ Proceso para realizar el pago -----------------------
    -------------------------------------------------------------------------------*/

function calcularTotalPagar(numMesa, tipoPago, keyPago) {
    console.log(numMesa, tipoPago, keyPago);
    resultadoCalculo = {};
    // Obtener los pedidos del localStorage
    let orders = JSON.parse(localStorage.getItem("orders"));

    // Filtrar los pedidos para la mesa seleccionada

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

    if (tipoPago === "todo") {
        let pedidosMesa = orders.filter(
            (order) => order.mesa === "Mesa " + numMesa
        );

        pedidosMesa.forEach((pedido, index) => {
            // Si el cliente decide pagar todo el pedido, agregar el total del pedido al total a pagar
            let totalPedido = 0;
            pedido.detalles.forEach((item) => {
                totalPedido += item.total;
                // Agregar la tupla a pagar al array
                tuplasAPagar.push({
                    mesa: numMesa,
                    pedidoNro: index + 1,
                    producto_id: item.producto_id,
                    forma: item.forma,
                    cantidad: item.cantidad,
                    precio_unitario: item.precio_unitario,
                    total: item.total,
                    tipo: "pagototal",
                });
            });
            // Agregar el total del pedido al total a pagar
            totalPagar += totalPedido;
        });
    } else if (tipoPago === "pedido") {
        let pedidosMesa = orders.filter((order) => order.key === keyPago);

        pedidosMesa.forEach((pedido, index) => {
            // Si el cliente decide pagar todo el pedido, agregar el total del pedido al total a pagar
            let totalPedido = 0;
            pedido.detalles.forEach((item) => {
                totalPedido += item.total;
                // Agregar la tupla a pagar al array
                tuplasAPagar.push({
                    keyPedido: keyPago,
                    mesa: numMesa,
                    pedidoNro: index + 1,
                    producto_id: item.producto_id,
                    forma: item.forma,
                    cantidad: item.cantidad,
                    precio_unitario: item.precio_unitario,
                    total: item.total,
                    tipo: "pagoPedido",
                });
            });
            // Agregar el total del pedido al total a pagar
            totalPagar += totalPedido;
        });
    } else if (tipoPago === "item") {
        let pedidosMesa = orders.filter(
            (order) => order.mesa === "Mesa " + numMesa
        );

        pedidosMesa.forEach((pedido, index) => {
            // Si el cliente decide pagar todo el pedido, agregar el total del pedido al total a pagar
            let totalPedido = 0;
            pedido.detalles.forEach((item) => {
                totalPedido += item.total;

                if (item.key === keyPago) {
                    // Agregar la tupla a pagar al array
                    tuplasAPagar.push({
                        mesa: numMesa,
                        pedidoNro: index + 1,
                        producto_id: item.producto_id,
                        keyitem: keyPago,
                        forma: item.forma,
                        cantidad: item.cantidad,
                        precio_unitario: item.precio_unitario,
                        total: item.total,
                        tipo: "pagoItem",
                    });

                    // Agregar el total del pedido al total a pagar
                    totalPagar += totalPedido;
                }
            });
        });
    } else {
        tuplasAPagar = [];
        totalPagar = 0;
    }

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
        descuentoBtn.disabled = false;
    } else {
        // Si el total a pagar es 0 o menor, deshabilitar el botón de pagar
        pagarBtn.disabled = true;
        descuentoBtn.disabled = true;
    }
}

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

    var subTotalSpan = document.querySelector(".subTotal");
    var ivaSpan = document.querySelector(".iva");
    var totalSpan = document.querySelector(".TOTAL");

    var subTotal = parseInt(subTotalSpan.textContent.replace(/\D/g, ""), 10);
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

    var cajero = document.getElementById("cajero").value; // Metodo de pago seleccionado

    var cliente = document.getElementById("cliente").value;

    console.log(subTotal);

    Livewire.emit("pagarEvent", {
        resultadoCalculo: resultadoCalculo,
        tipoOperacion: tipoOperacionSeleccionado,
        metodoPago: metodoPagoSeleccionado,
        imprimirRecibo: imprimirReciboSeleccionado,
        subTotal: subTotal,
        tipodescuento: tipoDescuentoSeleccionado,
        descuento: inputDescuentoValor,
        cajero: cajero,
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
                eliminarItemMostrador(detalle.producto_id, detalle.forma); // Llama a la función eliminarProducto con el índice del producto
            };

            // Agregar el botón al DOM
            cellEliminar.appendChild(botonEliminar);
        });
    });
}

function eliminarItemMostrador(producto, forma) {
    // Mostrar el SweetAlert de confirmación
    Swal.fire({
        title: "¿Está seguro?",
        text: "Esta acción eliminará el registro. ¿Está seguro que desea continuar?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        // Si el usuario confirma la eliminación
        if (result.isConfirmed) {
            // Llamar a la función para eliminar el pedido del local storage
            eliminarProductoLocalStorageMostrador(producto, forma);

            // Actualizar la vista filtrando los pedidos por mesa
            mostrarProductosMostrador();
        }
    });
}

function eliminarProductoLocalStorageMostrador(producto_id, forma) {
    let mesa = "MOSTRADOR";
    let orders = JSON.parse(localStorage.getItem("orders")) || [];
    let ordenesMostrador = orders.filter((order) => order.mesa === mesa);

    // Recorrer las órdenes en la sección de mostrador
    ordenesMostrador.forEach((orden, index) => {
        // Recorrer los detalles de la orden actual para buscar el producto_id
        orden.detalles.forEach((detalle, detalleIndex) => {
            if (detalle.producto_id === producto_id && detalle.forma === forma) {
                // Paso 1: Eliminar el detalle si se encuentra
                orden.detalles.splice(detalleIndex, 1);

                // Paso 2: Actualizar los datos en el localStorage
                localStorage.setItem("orders", JSON.stringify(orders));

                // Paso 3: Verificar si la orden no tiene más detalles
                if (orden.detalles.length === 0) {
                    // Eliminar la orden si no tiene más detalles
                    orders.splice(index, 1);
                    localStorage.setItem("orders", JSON.stringify(orders));
                    console.log("Orden eliminada porque ya no tiene detalles");
                }

                // Salir del bucle una vez que se elimine el detalle
                return;
            }
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
                forma: detalle.forma,
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

function addProductosMostrador() {
    $("#searchproductrestaurant").modal("show");
    // Acciones que deseas realizar cuando se realiza un doble clic en el mostrador
}

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
    }
});

/*-----------------------Alert despues de guardado --------------------------------------------*/

window.addEventListener("venta-generada", (event) => {
    const numeroVenta = event.detail.venta;
    const tuplasAEliminar = event.detail.tuplas;

    tuplasAEliminar.forEach((tupla) => {

        let mesa = tupla.mesa;
        // Suponiendo que la clave única está en la propiedad 'key'

        if (mesa === "MOSTRADOR") {
            eliminarPedidoLocalStorageMostrador();
            return;
        } else {

            if(tupla.tipo === 'pagototal'){
                eliminarLocalStoragePagoTotalMesa(mesa);
                return;
            } else if(tupla.tipo === 'pagoPedido') {
                eliminarLocalStoragePagoPedido(tupla.keyPedido);
                return;
            } else if(tupla.tipo === 'pagoItem'){
                eliminarLocalStoragePagoItem(mesa, tupla.keyitem);
                return;
            }

        }
    });

     Swal.fire({
        icon: "success",
        title: "Venta realizada correctamente",
        text: `Número de venta: ${numeroVenta}`,
        showConfirmButton: false,
        timer: 1500,
    });
    setTimeout(() => {
        location.reload();
    }, 1500);
});



function eliminarLocalStoragePagoItem(mesa, keyitem) {
    let mesaString = "Mesa " + mesa;

    // Obtener los pedidos del localStorage
    let orders = JSON.parse(localStorage.getItem("orders")) || [];

    // Filtrar los pedidos para la mesa especificada
    let pedidosMesa = orders.filter((order) => order.mesa === mesaString);

    // Recorrer los pedidos para buscar y eliminar el item
    pedidosMesa.forEach((pedido) => {
        // Buscar el detalle que coincide con la keyitem
        let detalleIndex = pedido.detalles.findIndex((detalle) => detalle.key === keyitem);

        // Si se encontró el detalle, eliminarlo del pedido
        if (detalleIndex !== -1) {
            pedido.detalles.splice(detalleIndex, 1);

            // Si el pedido queda sin detalles, eliminar el pedido completo
            if (pedido.detalles.length === 0) {
                orders.splice(orders.indexOf(pedido), 1);
            }
        }
    });

    // Guardar el array actualizado en el localStorage
    localStorage.setItem("orders", JSON.stringify(orders));

    filtrarPedidosPorMesa(mesa);
}


function eliminarLocalStoragePagoTotalMesa(mesa)
{
    let mesa_eliminar = "Mesa" + " " + mesa;

    let orders = JSON.parse(localStorage.getItem("orders")) || [];

    let pedidosEliminar = orders.filter((order) => order.mesa === mesa_eliminar);

    // Obtiene los índices de los pedidos que coinciden con el criterio de filtrado
    let indicesEliminar = pedidosEliminar.map(order => orders.indexOf(order));

    // Elimina los pedidos correspondientes utilizando los índices obtenidos
    indicesEliminar.forEach(index => orders.splice(index, 1));

    // Guarda el array actualizado en el localStorage
    localStorage.setItem("orders", JSON.stringify(orders));

}

function eliminarLocalStoragePagoPedido(keyPedido)
{

     let pedido_eliminar = keyPedido;

    let orders = JSON.parse(localStorage.getItem("orders")) || [];

    let pedidosEliminar = orders.filter((order) => order.key === pedido_eliminar);

    // Obtiene los índices de los pedidos que coinciden con el criterio de filtrado
    let indicesEliminar = pedidosEliminar.map(order => orders.indexOf(order));

    // Elimina los pedidos correspondientes utilizando los índices obtenidos
    indicesEliminar.forEach(index => orders.splice(index, 1));

    // Guarda el array actualizado en el localStorage
    localStorage.setItem("orders", JSON.stringify(orders));

}

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

/*----------------------Procesos script modal agregar productos mesa ---------------------------*/

function saveOrder() {
    // Obtener la mesa desde el contenido de la etiqueta h5
    let mesa = document.getElementById("solicitantePedido").textContent.trim();
    let etiquetaInput = document.getElementById("etiqueta");

    if (etiquetaInput.value.trim() === "") {
        mensajeError.textContent = "Por favor, ingrese una etiqueta.";
        mensajeError.style.display = "inline"; // Mostrar el mensaje de error
        return; // Salir de la función si el campo de la etiqueta está vacío
    } else {
        mensajeError.textContent = ""; // Limpiar el mensaje de error si la validación pasa
        mensajeError.style.display = "none"; // Ocultar el mensaje de error
        // Aquí puedes hacer más acciones si la validación pasa
    }

    // Verificar si ya hay un pedido para esta mesa en el localStorage
    let pedidoNro = 1; // Valor predeterminado para el primer pedido
    let existingOrders = JSON.parse(localStorage.getItem("orders"));
    if (existingOrders) {
        // Buscar pedidos existentes para esta mesa
        let mesaOrders = existingOrders.filter((order) => order.mesa === mesa);
        if (mesaOrders.length > 0) {
            // Si hay pedidos existentes, obtener el número de pedido más alto y aumentarlo en 1
            pedidoNro =
                Math.max(...mesaOrders.map((order) => order.pedidoNro)) + 1;
        }
    }

    // Obtener los detalles del pedido (productos, cantidades, precios, totales) desde el carrito
    let detallesPedido = cart.map((product, index) => {
        // Generar una llave única para cada ítem del pedido
        const itemKey = `item_${pedidoNro}_${index}`;
        return {
            producto_id: product.id,
            forma: product.forma,
            cantidad: product.cantidad,
            nombre: product.name,
            precio_unitario: product.precio_venta,
            total: product.total,
            key: itemKey, // Agregar la llave única al objeto del ítem del pedido
        };
    });

    // Verificar si hay productos en la orden
    if (detallesPedido.length === 0) {
        alert("La orden no tiene productos.");
        return; // Salir de la función si no hay productos en la orden
    }

    let etiqueta = etiquetaInput.value.trim();

    // Generar una llave única para el pedido
    const pedidoKey = `pedido_${mesa}_${pedidoNro}`;

    // Construir el nuevo pedido
    let nuevoPedido = {
        key: pedidoKey, // Agregar la llave única al objeto del pedido
        mesa: mesa,
        pedidoNro: pedidoNro,
        etiqueta: etiqueta,
        detalles: detallesPedido,
    };

    // Obtener los pedidos existentes del localStorage o inicializar un nuevo array si no hay ninguno
    let orders = existingOrders || [];

    // Agregar el nuevo pedido al array de pedidos
    orders.push(nuevoPedido);

    // Guardar el array de pedidos actualizado en el localStorage
    localStorage.setItem("orders", JSON.stringify(orders));

    // Limpiar el carrito después de guardar el pedido
    cart = [];
    updateCartView();

    // Actualizar el estado de las mesas

    // Cerrar el modal

    location.reload();
}

/*-----------------SubModal añadir cantidad productos mesa -----------------------*/

// Obtener una referencia al botón "cerrarModalCantidad"
var cerrarModalCantidadBtn = document.getElementById("cerrarModalCantidad");
var cancelarModalCantidadBtn = document.getElementById("cancelarModalCantidad");

cerrarModalCantidadBtn.addEventListener("click", cerrarModalCantidadModal);
cancelarModalCantidadBtn.addEventListener("click", cerrarModalCantidadModal);

function cerrarModalCantidadModal() {
    var modalAnterior = document.getElementById("cantidadModal");
    modalAnterior.style.display = "none";

    document.getElementById("selectPresentacion").value = ""; // Reinicia el select
    document.getElementById("cantidadInput").value = "1"; // Reinicia la cantidad a 1
    document.getElementById("precioUnitarioInput").value = "1"; // Reinicia el precio unitario a 1
    document.getElementById("totalPrecioCompraInput").value = "1";
}
