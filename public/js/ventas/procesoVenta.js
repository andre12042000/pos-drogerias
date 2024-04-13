 /*--------------------Asignación de variables a objetos html ---------------*/
 var ivaUnitario = 0; //Usado en el modal de editar Item
 var totalDescontado = 0;
 let indiceItem;
 let productoEditar;
 const tipoOperacion = document.getElementById('tipoOperacion');
 const botonEjecutar = document.getElementById('pagarBtn');
 const nroCotizacion = document.getElementById('codigo_cotizacion');
 const cliente = document.getElementById('cliente');
 const mensajeErrorCliente = document.getElementById('cambiarClienteHelp');
 const mensajeErrorMetodoPagoHelp = document.getElementById('cambiarMetodoPagoHelp');

 const radioSi = document.getElementById('opcionSi');
 const radioNo = document.getElementById('opcionNo');

 const buscarProductoCodigo = document.getElementById('buscarProductoCodigo');

 const MetodoPago = document.getElementById('selectMetodoPago');
 const CantidadPagada = document.getElementById('inputCantidadPagada');

 const spanSubTotal = document.querySelector('.subtotal');
 const spanDescuento = document.querySelector('.descuento');
 const spanIva = document.querySelector('.iva');
 const spanTotal = document.querySelector('.total');

 // Inicializar los elementos span en 0
 spanSubTotal.textContent = '0';
 spanDescuento.textContent = '0';
 spanIva.textContent = '0';
 spanTotal.textContent = '0';

 // Manejar valores adecuadamente (por ejemplo, convertir texto a números si es necesario)
 const subTotal = spanSubTotal ? parseFloat(spanSubTotal.textContent) : 0;
 const descuento = spanDescuento ? parseFloat(spanDescuento.textContent) : 0;
 const iva = spanIva ? parseFloat(spanIva.textContent) : 0;
 const total = spanTotal ? parseFloat(spanTotal.textContent) : 0;


 // Variables Modal Editar Item

 const selectPresentacionEdit = document.getElementById("selectPresentacionEdit");
 const cantidadInputEdit = document.getElementById("cantidadInputEdit");
 const precioUnitarioInputEdit = document.getElementById("precioUnitarioInputEdit");
 const totalPrecioCompraInputEdit = document.getElementById("totalPrecioCompraInputEdit");
 const ivaInputEdit = document.getElementById("ivaInputEdit");
 const inputDescuento = document.getElementById("inputDescuento");
 const descuentoPorcentaje = document.getElementById('descuentoPorcentaje');
 const descuentoValorFijo = document.getElementById('descuentoValorFijo');
 const btnIncrementarEditar = document.getElementById("btnIncrementarEditar");
 const btnDecrementarEditar = document.getElementById("btnDecrementarEditar");
 const cancelarTransaccionBtn = document.getElementById("cancelarTransaccion");
 const cerrarModalBtn = document.getElementById("cerrarModalBtn");
 const descuentoBtn = document.getElementById("descuentoBtn");
 const actualizarItemVentaBtn = document.getElementById('actualizarItemVenta');

 // Agregar el evento onchange a los radio inputs
 descuentoPorcentaje.addEventListener('change', handleChange);
 descuentoValorFijo.addEventListener('change', handleChange);



 botonEjecutar.addEventListener('click', function() {
     // Código a ejecutar cuando se haga clic en el botón
     ejecutarProcesoGuardado();
     // Por ejemplo, puedes agregar aquí la lógica para procesar el pago
 });


 document.addEventListener('DOMContentLoaded', function() {
     asignarValoresTotalesAVista();
     calcularTotales();
 });

 MetodoPago.onchange = function() {
     if (MetodoPago.value === '1') {
         radioSi.checked = true;
     } else {
         radioNo.checked = true;
     }
 };


 function asignarValoresTotalesAVista() {
     // Asignar valores a elementos HTML
     document.querySelector('.subtotal').textContent = subTotal;
     document.querySelector('.descuento').textContent = descuento;
     document.querySelector('.iva').textContent = iva;
     document.querySelector('.total').textContent = total;
 }

 function cambiarEstadoBotonPagar($value) {
     var btnPagar = botonEjecutar;

     if ($value > 0) {
         btnPagar.disabled = false;
     } else {
         btnPagar.disabled = true;
     }

 }


 function actualizarBoton() {
     var Total = spanTotal.textContent;
     const indiceSeleccionado = tipoOperacion.value;
     var btnPagar = botonEjecutar;
     var textoBtn = '';


     var selectMetodoPago = MetodoPago;
     var inputCantidadPagada = CantidadPagada;

     // Cambiar estado de los elementos selectMetodoPago e inputCantidadPagada
     if (indiceSeleccionado === 'VENTA') {
         selectMetodoPago.disabled = false;
         inputCantidadPagada.disabled = false;
     } else {
         selectMetodoPago.disabled = true;
         inputCantidadPagada.disabled = true;
     }

     switch (indiceSeleccionado) {
         case 'VENTA':
             textoBtn = 'PAGAR';
             cambiarClienteHelp.style.display = 'none';
             break;
         case 'CREDITO':
             textoBtn = 'GUARDAR VENTA CRÉDITO';
             if (cliente.value === '1') {
                 cambiarClienteHelp.style.display = 'block';
             } else {
                 cambiarClienteHelp.style.display = 'none';
             }
             break;
         case 'CONSUMO_INTERNO':
             textoBtn = 'GUARDAR CONSUMO INTERNO';
             cambiarClienteHelp.style.display = 'none';
             break;
         case 'COTIZACION':
             textoBtn = 'REALIZAR COTIZACIÓN';
             if (cliente.value === '1') {
                 cambiarClienteHelp.style.display = 'block';
             } else {
                 cambiarClienteHelp.style.display = 'none';
             }

             break;
         default:
             textoBtn = '';
             break;
     }

     btnPagar.querySelector('strong').innerText = textoBtn;

     calcularTotales();
 }

 function mostrarDatosLocalStorageEnTabla() {
     var orders = JSON.parse(localStorage.getItem('ordersPos')) || [];
     var tablaBody = document.querySelector('#tablaProductos tbody');

     // Limpiar tabla antes de agregar datos
     tablaBody.innerHTML = '';

     orders.forEach(function(order, index) {
         var row = tablaBody.insertRow(); // Insertar una nueva fila en la tabla

         // Insertar celdas en la fila
         row.insertCell().textContent = index + 1; // Índice de orden
         row.insertCell().textContent = order.code; // Código del producto
         row.insertCell().textContent = order.nombre; // Descripción del producto

         // Agregar evento doble clic a la fila
         row.addEventListener('dblclick', function() {
             mostrarModalEditarItem(order, index); // Pasar el objeto de pedido como argumento
         });

         // Mostrar el texto correspondiente según la forma del producto
         var formaText = '';
         if (order.forma === 'disponible_caja') {
             formaText = 'Caja';
         } else if (order.forma === 'disponible_blister') {
             formaText = 'Blister';
         } else if (order.forma === 'disponible_unidad') {
             formaText = 'Unidad';
         }
         row.insertCell().textContent = formaText; // Mostrar la forma del producto

         // Precio unitario formateado como moneda
         var precioUnitarioCell = row.insertCell();
         precioUnitarioCell.textContent = formatCurrency(order.precio_unitario);
         precioUnitarioCell.classList.add('align-right'); // Alinear a la derecha

         // Cantidad del producto
         row.insertCell().textContent = order.cantidad;

         // IVA formateado como moneda
         var ivaCell = row.insertCell();
         if (order.iva > 100) {
             ivaCell.textContent = formatCurrency(order.iva);
             ivaCell.classList.add('align-right'); // Alinear a la derecha
         } else {
             ivaCell.textContent = '$' + 0;
             ivaCell.classList.add('align-right'); // Alinear a la derecha
         }

         // Descuento formateado como moneda
         var descuentoCell = row.insertCell();
         descuentoCell.textContent = formatCurrency(order.descuento);
         descuentoCell.classList.add('align-right'); // Alinear a la derecha

         // Total formateado como moneda
         var totalCell = row.insertCell();
         totalCell.textContent = formatCurrency(order.total);
         totalCell.classList.add('align-right'); // Alinear a la derecha

         // Agregar ícono de eliminar
         var opcionesCell = row.insertCell();
         var iconEliminar = document.createElement('i');
         iconEliminar.classList.add('fas', 'fa-trash-alt', 'btn-eliminar');
         opcionesCell.appendChild(iconEliminar);
         opcionesCell.classList.add('align-center'); // Alinear a la derecha

         // Agregar evento mouseover al ícono de eliminar
         iconEliminar.addEventListener('mouseover', function() {
             iconEliminar.style.cursor = 'pointer';
         });

         // Agregar evento click al ícono de eliminar
         iconEliminar.addEventListener('click', function() {
             eliminarOrden(index);
         });


     });

     calcularTotales();
 }

 function calcularTotales() {
     var orders = JSON.parse(localStorage.getItem('ordersPos')) || [];

     var subtotal = 0;
     var descuentoTotal = 0;
     var ivaTotal = 0;
     var total = 0;

     // Recorrer los productos y calcular los totales
     orders.forEach(function(order) {
         total += order.precio_unitario * order.cantidad; // Calcular subtotal
         descuentoTotal += order.descuento; // Sumar descuentos
         ivaTotal += order.iva; // Sumar impuestos
     });

     // Calcular total sumando el subtotal, el impuesto y restando el descuento
     //total = subtotal + ivaTotal - descuentoTotal;

     subtotal = total - (ivaTotal - descuentoTotal);

     spanSubTotal.textContent = formatCurrency(subtotal);
     spanDescuento.textContent = formatCurrency(descuentoTotal);
     spanIva.textContent = formatCurrency(ivaTotal);
     spanTotal.textContent = formatCurrency(total);

     cambiarEstadoBotonPagar(total);

     return {
         subtotal: subtotal,
         descuentoTotal: descuentoTotal,
         ivaTotal: ivaTotal,
         total: total
     };

 }

 // Función para formatear un valor como moneda en pesos sin decimales
 function formatCurrency(amount) {
     return '$' + amount.toLocaleString('es-ES', {
         minimumFractionDigits: 0
     });
 }

 // Función para eliminar una orden del localStorage
 function eliminarOrden(index) {
     let orders = JSON.parse(localStorage.getItem("ordersPos")) || [];

     // Verificar si el índice está dentro del rango de la matriz
     if (index >= 0 && index < orders.length) {
         // Eliminar el elemento en el índice dado
         orders.splice(index, 1);
         // Actualizar el localStorage con los datos actualizados
         localStorage.setItem("ordersPos", JSON.stringify(orders));
         // Luego, puedes actualizar la tabla o cualquier otra cosa que necesites hacer después de eliminar la orden.
     } else {
         console.error("Índice fuera de rango.");
     }

     mostrarDatosLocalStorageEnTabla();


 }

 /*------------------------------------------Proceso guardado de datos --------------------------------------*/

 function ejecutarProcesoGuardado() {
     validaciones();

     const orderPosData = localStorage.getItem('ordersPos');
     if (orderPosData) {
         // Convertir los datos de formato JSON a un array de JavaScript
         var orderPosArray = JSON.parse(orderPosData);
     } else {
         let mensaje = 'Ops!, ocurrio un error con la data, comuniquese con el administrador del sistema';
         mostrarError(mensaje);
     }

     const radios = document.querySelectorAll('input[name="opcionRadio"]');
     let imprimir;
     // Iterar sobre los radios para verificar cuál está seleccionado
     radios.forEach(radio => {
         // Obtener el valor del radio seleccionado
         imprimir = radio.value;
     });

     let totales = calcularTotales();

     const datos = {
         'tipoOperacion': tipoOperacion.value,
         'cliente_id': cliente.value,
         'productos': orderPosArray,
         'metodoPago': MetodoPago.value,
         'imprimir': imprimir,
         'totales': totales,
     };

     Livewire.emit('almacenarTransaccion', datos);

 }

 function validaciones() {

     var total = spanTotal.textContent.trim(); // Elimina espacios en blanco al inicio y al final

     if (total == '0' && total == '') {
         let mensaje = 'Por favor, agrega al menos un producto para comenzar.'
         mostrarError(mensaje);
         return;
     }


     if (tipoOperacion.value === 'COTIZACION' || tipoOperacion.value === 'CREDITO') {
         if (cliente.value === 1 || cliente.value === '') {
             cambiarClienteHelp.style.display = 'block';
             return;
         } else {
             cambiarClienteHelp.style.display = 'none';
         }
     }


     if (tipoOperacion.value === 'VENTA') {
         if (MetodoPago.value === '3') {
             cambiarMetodoPagoHelp.style.display = 'block';
             return;
         } else {
             cambiarMetodoPagoHelp.style.display = 'none';
         }

     }

 }

 function verificarMetodoPagoConProceso() {
     if (tipoOperacion.value === 'VENTA' && MetodoPago.value === '3') {
         cambiarMetodoPagoHelp.style.display = 'block';
     } else {
         cambiarMetodoPagoHelp.style.display = 'none';
     }

 }

 // Metodo para editar pedido

 function mostrarModalEditarItem(producto, index) {
     const modal = document.getElementById("editProductSaleModal");
     indiceItem = index;
     productoEditar = producto;

     const modalContent = document.getElementById("modalContent");


     selectPresentacionEdit.value = producto.forma;
     cantidadInputEdit.value = producto.cantidad;
     precioUnitarioInputEdit.value = producto.precio_unitario;
     totalPrecioCompraInputEdit.value = producto.subtotal;
     ivaInputEdit.value = producto.iva;

     if (producto.iva > 0) {
         ivaUnitario = obtenerIvaUnitario(producto);
     } else {
         ivaUnitario = 0;
     }

     if (producto.descuento > 0) {
         // Si el descuento es mayor que 0, habilitar el input de descuento
         inputDescuento.disabled = false;
         inputDescuento.value = producto.descuento;

         // Seleccionar el radio de descuento fijo
         descuentoValorFijo.checked = true;
         // Deseleccionar el radio de descuento porcentaje
         descuentoPorcentaje.checked = false;
         descuentoBtn.disabled = true;
     } else {
         // Si el descuento es 0, deshabilitar el input de descuento
         inputDescuento.disabled = true;
         inputDescuento.value = 0;

         // Desseleccionar ambos radios
         descuentoValorFijo.checked = false;
         descuentoPorcentaje.checked = false;
     }

     calcularTotalEditItem();
     // Mostrar el modal
     modal.style.display = "block";


 }

 inputDescuento.addEventListener('change', function() {
     calcularTotalEditItem
 (); // Llamar a la función calcularTotalEditItem cuando cambie el valor del inputDescuento
 });

 // Función para manejar el evento onchange de los radio inputs
 function handleChange() {
     if (descuentoPorcentaje.checked) {
         // Si se selecciona el descuento porcentaje, habilitar el inputDescuento
         inputDescuento.disabled = false;
         inputDescuento.maxLength = 2; // Establecer el máximo a 2 dígitos
         inputDescuento.placeholder = 'Ingrese porcentaje (%)';
     } else if (descuentoValorFijo.checked) {
         // Si se selecciona el descuento valor fijo, habilitar el inputDescuento
         inputDescuento.disabled = false;
         inputDescuento.maxLength =
         totalPrecioCompraInputEdit; // Establecer el máximo al valor almacenado en totalPrecioCompraInputEdit
         inputDescuento.placeholder = 'Ingrese valor fijo ($)';
     }
 }

 function obtenerIvaUnitario(producto) {
     const valorIvaUnitario = producto.iva / producto.cantidad;

     return valorIvaUnitario;
 }

 btnIncrementarEditar.addEventListener("click", function() {
     // Obtener el valor actual del input y convertirlo a un número
     let cantidad = parseInt(cantidadInputEdit.value);

     // Incrementar la cantidad
     cantidad += 1;

     // Actualizar el valor del input
     cantidadInputEdit.value = cantidad;

     calcularTotalEditItem();
 });

 btnDecrementarEditar.addEventListener("click", function() {
     // Obtener el valor actual del input y convertirlo a un número
     let cantidad = parseInt(cantidadInputEdit.value);

     // Incrementar la cantidad
     cantidad -= 1;

     // Actualizar el valor del input
     cantidadInputEdit.value = cantidad;
     calcularTotalEditItem();
 });

 function calcularTotalEditItem() {
     const cantidad = parseFloat(cantidadInputEdit.value);
     const precio = parseFloat(precioUnitarioInputEdit.value);
     const descuento = parseFloat(inputDescuento.value);

     // Verificar si las entradas son números válidos
     if (!isNaN(cantidad) && !isNaN(precio)) {
         // Calcular el total sin descuento
         let total = cantidad * precio;

         // Aplicar el descuento si hay un valor en el inputDescuento
         if (!isNaN(descuento)) {
             if (descuento > 0) {
                 // Si hay descuento, determinar si es por valor fijo o porcentaje
                 if (descuento > 99) {
                     // Descuento por valor fijo
                     total -= descuento; // Restar el valor fijo al total
                     totalDescontado = descuento;
                 } else {
                     // Descuento por porcentaje
                     totalDescontado = total * (descuento / 100); // Calcular el descuento por porcentaje
                     total -= totalDescontado; // Restar el descuento al total
                 }
             } else {
                 totalDescontado = 0; // No hay descuento
             }
         }

         // Calcular el IVA
         ivaInputEdit.value = Math.round(ivaUnitario * cantidad);

         // Actualizar el input de total con el descuento aplicado
         totalPrecioCompraInputEdit.value = total.toFixed(0);
     } else {
         spanTotal.textContent = "Error"; //
     }
 }

 actualizarItemVentaBtn.addEventListener('click', function() {
     let ordersPos = JSON.parse(localStorage.getItem('ordersPos')) || [];

     let item_actualizar = {};

     item_actualizar.key = productoEditar.key;
     item_actualizar.producto_id = productoEditar.producto_id;
     item_actualizar.forma = productoEditar.forma;
     item_actualizar.nombre = productoEditar.nombre;
     item_actualizar.code = productoEditar.code;
     item_actualizar.cantidad = cantidadInputEdit.value;
     item_actualizar.precio_unitario = precioUnitarioInputEdit.value;
     item_actualizar.descuento = totalDescontado;
     item_actualizar.total = totalPrecioCompraInputEdit.value;
     item_actualizar.iva = ivaUnitario;

     ordersPos[indiceItem] = item_actualizar;

     // Actualizar el localStorage con el array actualizado ordersPos
     localStorage.setItem('ordersPos', JSON.stringify(ordersPos));

     mostrarDatosLocalStorageEnTabla();

     cerrarModalYLimpiar();

 });

 function cerrarModalYLimpiar() {
     const modal = document.getElementById("editProductSaleModal");

     modal.style.display = "none";


     ivaUnitario = 0;
     productoEditar = '';
     indiceItem = '';
     ivaInputEdit.value = '';
     precioUnitarioInputEdit.value = '';
     inputDescuento.value = '';
     selectPresentacionEdit = '';
     cantidadInputEdit = '';
     totalPrecioCompraInputEdit = '';




     // Puedes agregar más limpieza de variables aquí según sea necesario

 }

cerrarModalBtn.addEventListener('click', cerrarModalYLimpiar);



 document.querySelectorAll('.fila-datos').forEach(function(row) {
     row.addEventListener('dblclick', function() {
         var index = this.rowIndex - 1; // Restar 1 para obtener el índice correcto
         var orders = JSON.parse(localStorage.getItem('ordersPos')) || [];
         var order = orders[index];
         mostrarModalEditarItem(order);
     });
 });




 // Llamar a la función para mostrar los datos del localStorage en la tabla cuando se cargue la página
 mostrarDatosLocalStorageEnTabla();

 /*------------------------------------------------Alertas sweet Alert ---------------------------------*/
 function mostrarError(mensaje) {
     Swal.fire({
         icon: 'error',
         title: 'Error',
         text: mensaje
     });
 }


 window.addEventListener("proceso-guardado", (event) => {
     const numeroVenta = event.detail.venta;
     limpiarLocalStorage();


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

 function limpiarLocalStorage() {
     let orders = JSON.parse(localStorage.getItem("ordersPos")) || [];

     // Filtrar los pedidos que quieres mantener en un nuevo array
     let filteredOrders = orders.filter(order => {
         return false; // Esto elimina todos los pedidos
     });

     // Actualizar el localStorage con los datos filtrados
     localStorage.setItem("ordersPos", JSON.stringify(filteredOrders));
 }



