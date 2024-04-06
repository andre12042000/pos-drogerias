<div>
    @include('popper::assets')
    <div id="alerts"> @include('includes.alert')</div>

    <div class="card">
        <div class="card-header">
            <div class="container">

                <div class="row mb-2 mt-3">
                    <div class="col-sm-4">
                        <select class="form-select" aria-label="Default select example" id="tipoOperacion"
                            onchange="actualizarBoton()">
                            <option value="">Seleccione una opción</option>
                            <option value="VENTA" selected>VENTA</option>
                            <option value="CREDITO">VENTA CRÉDITO</option>
                            <option value="CONSUMO_INTERNO">CONSUMO INTERNO</option>
                            <option value="COTIZACION">COTIZACIÓN</option>
                        </select>
                    </div>

                    <div class="col-sm-4">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="codigo_cotizacion"
                                placeholder="Nro. cotización">
                        </div>
                    </div>

                    <div class="col-sm-4">

                        <div class="input-group" style="height: 38px; max-width: 100%;">
                            <select class="form-select js-example-basic-single" name="cliente" id="cliente">
                                <option value="1">Consumidor final</option>
                                @forelse ($clientes as $client)
                                    <option value="{{ $client->id }}">{{ mb_strtoupper($client->name) }}</option>
                                @empty
                                    <option value="">No hay registros disponibles...</option>
                                @endforelse

                            </select>
                            <div class="input-group-append" id="button-addon4">
                                <button class="btn btn-outline-secondary" type="button"data-toggle="modal"
                                    data-target="#clientmodal"><i class="bi bi-plus-circle-fill"
                                        style="cursor: pointer"></i></button>
                            </div>
                        </div>
                        <div id="cambiarClienteHelp" class="text-danger" style="display: none;">
                            ¡Es necesario cambiar de cliente!
                        </div>
                    </div>

                    {{--   <div class="col-sm-3">
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" value="" id="factura_electronica"
                                disabled>
                            <label class="form-check-label" for="flexCheckDefault">
                                Facturar electrónicamente
                            </label>
                        </div>

                    </div> --}}
                </div>

                <div class="row">
                    <div class="col">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                @if ($empresa->modal_aut_produc == 1)
                                @endif
                                <i class="bi bi-search mr-3 mt-2" style="cursor: pointer" data-toggle="modal"
                                    data-target="#searchproduct"></i>
                            </div>
                            <input type="text"
                                class="form-control @if ($error_search) is-invalid @endif"
                                id="buscarProductoCodigo" placeholder="Buscar producto por código" autofocus
                                autocomplete="disabled"> <!--Buscador por código -->
                        </div>
                        @error('codigo_de_producto')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="card" style="height: 300px; overflow-y: auto;">
        <div class="card-body">
            <div class="table-responsive-md">
                <table class="table" id="tablaProductos">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Código</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Forma</th>
                            <th scope="col" class="text-end">Precio Unit.</th>
                            <th scope="col" class="text-center">Cant.</th>
                            <th scope="col" class="text-end">Iva</th>
                            <th scope="col" class="text-end">Descuento</th>
                            <th scope="col" class="text-end">Total</th>
                            <th scope="col" class="text-center">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="container">


            <div class="row mt-3">
                <div class="col">
                    <div class="col">
                        <label for="exampleFormControlInput1" class="form-label">Imprimir recibo</label>
                        <br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="opcionRadio" id="opcionSi"
                                value="1">
                            <label class="form-check-label" for="opcionSi">Si</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="opcionRadio" id="opcionNo"
                                value="0" checked>
                            <label class="form-check-label" for="opcionNo">No</label>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-floating">
                        <select class="form-select" id="selectMetodoPago" name="metodoPagoSelect"
                            aria-label="Floating label select example" onchange="verificarMetodoPagoConProceso()">
                            @foreach ($metodos_pago as $metodo)
                                <option value="{{ $metodo->id }}" @if ($metodo->id == '2') selected @endif>
                                    {{ $metodo->name }}</option>
                            @endforeach
                        </select>
                        <label for="floatingSelect">Método de pago</label>
                        <div id="cambiarMetodoPagoHelp" class="text-danger text-center" style="display: none;">
                            ¡Por favor selecciona otro método de pago!
                        </div>
                    </div>

                    <div class="form-floating mt-2">
                        <input type="number" class="form-control" id="inputCantidadPagada"
                            name="inputCantidadPagada" placeholder="name@example.com">
                        <label for="floatingInput">Cantidad Pagada</label>
                    </div>
                    <div class="form-floating mt-2">
                        <input type="number" class="form-control" id="inputCambio" name="inputCambio"
                            placeholder="Password" disabled>
                        <label for="floatingPassword">Cambio</label>
                    </div>

                </div>
                <div class="col columna-cuentas">
                    <div class="costo-compra mt-2">
                        <label>Subtotal:</label>
                        <span class="subtotal"></span>
                    </div>
                    <div class="costo-compra mt-2">
                        <label>Descuento:  </label>
                        <span class="descuento"></span>
                    </div>

                    <div class="costo-compra mt-2">
                        <label>IVA:</label>
                        <span class="iva"></span>
                    </div>
                    <div class="costo-compra mt-1">
                        <label>Total:</label>
                        <span class="total"></span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 mt-4">
                    <div class="d-grid">
                        <button class="btn btn-primary btn-pagar" id="pagarBtn" type="button" disabled>
                            <strong>PAGAR</strong>
                            <img class="loader" src="{{ asset('img/loading.gif') }}" alt="Cargando..."
                                width="30px;" style="display: none;" />
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
@include('modals.sale.edit_product_added_to_sale')
@include('modals.sale.add_product_code')



<script>
    /*--------------------Asignación de variables a objetos html ---------------*/
    var ivaUnitario = 0; //Usado en el modal de editar Item
    var totalDescontado = 0;
    let indiceItem;
    let productoEditar;
    const tipoOperacion = document.getElementById('tipoOperacion');
    const botonEjecutar = document.getElementById('pagarBtn');
    const inputCodigoCotizacion = document.getElementById('codigo_cotizacion');
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


    /*---------------------Variables para el modal de crear producto por code -----------------------*/

    const ivaInputCreate = document.getElementById("ivaInputCreate");
    const selectPresentacionCreate = document.getElementById("selectPresentacionCreate");
    const cantidadInputCreate = document.getElementById("cantidadInputCreate");
    const inputDescuentoCreate = document.getElementById("inputDescuentoCreate");
    const totalPrecioCompraInputCreate = document.getElementById("totalPrecioCompraInputCreate");
    const precioUnitarioInputCreate = document.getElementById("precioUnitarioInputCreate");
    const cancelarTransaccionCreate = document.getElementById("cancelarTransaccionCreate");
    const guardarItemVenta = document.getElementById("guardarItemVenta");

    const descuentoPorcentajeCreate = document.getElementById('descuentoPorcentajeCreate');
    const descuentoValorFijoCreate = document.getElementById('descuentoValorFijoCreate');
    const btnIncrementarCreate = document.getElementById("btnIncrementarCreate");
    const btnDecrementarCreate = document.getElementById("btnDecrementarCreate");

    const cerrarModalBtnCreate = document.getElementById("cerrarModalBtnCreate");
    const descuentoBtnCreate = document.getElementById("descuentoBtnCreate");

    descuentoPorcentajeCreate.addEventListener('change', handleChangeDescuentoCreate);
    descuentoValorFijoCreate.addEventListener('change', handleChangeDescuentoCreate);


    /*---------------------Buscar producto codigo de barras --------------------------------------*/

    buscarProductoCodigo.addEventListener('keyup', function(event) {
        if (event.keyCode === 13) {
            // Obtener el valor del input
            const valorInput = buscarProductoCodigo.value;

            if (valorInput.length > 3) {
                // Emitir un evento Livewire con el valor como parámetro
                Livewire.emit('buscarProductoCodigo', valorInput);
            } else {
                // Aquí puedes agregar una acción adicional si el valor no supera los 3 dígitos, por ejemplo, mostrar un mensaje de error.
                console.log("El valor ingresado debe tener más de 3 dígitos.");
            }
        }
    });

    window.addEventListener('seleccionarProductoEvent', function(event) { //Evento que trae los datos desde el backend

        // Mostrar el modal
        const modal = document.getElementById("addProductCode");
        const modalContent = document.getElementById("modalContent");
        modal.style.display = "block";

        productoEditar = event.detail;

        let precioUnico = obtenerPrecioSuperiorACero(productoEditar);
        let tipo;
        let precioventa;

        if (precioUnico) {
            if (precioUnico === "precio_caja") {
                tipo = 'disponible_caja';
                precioventa = productoEditar.precio_caja;



            } else if (precioUnico === "precio_blister") {
                tipo = 'disponible_blister';
                precioventa = productoEditar.precio_blister; // Corregido aquí
            } else {
                tipo = 'disponible_unidad';
                precioventa = productoEditar.precio_unidad; // Corregido aquí
            }

            selectPresentacionCreate.value = tipo;
            precioUnitarioInputCreate.value = precioventa;
            totalPrecioCompraInputCreate.value = precioventa;

            deshabilitarOpcionesSelect(precioUnico);
        }

    });

    function deshabilitarOpcionesSelect(precioUnico)
    {
        const opciones = selectPresentacionCreate.options;

        // Iterar sobre las opciones y deshabilitar según la condición
        for (let i = 0; i < opciones.length; i++) {
            if (precioUnico === "precio_caja") {
                if (opciones[i].value === 'disponible_blister' || opciones[i].value === 'disponible_unidad') {
                    opciones[i].disabled = true;
                }
            } else if (precioUnico === "precio_blister") {
                if (opciones[i].value === 'disponible_caja' || opciones[i].value === 'disponible_unidad') {
                    opciones[i].disabled = true;
                }
            } else {
                if (opciones[i].value === 'disponible_caja' || opciones[i].value === 'disponible_blister') {
                    opciones[i].disabled = true;
                }
            }
        }
    }

    btnIncrementarCreate.addEventListener("click", function() {
        // Obtener el valor actual del input y convertirlo a un número
        let cantidad = parseInt(cantidadInputCreate.value);

        // Incrementar la cantidad
        cantidad += 1;

        // Actualizar el valor del input
        cantidadInputCreate.value = cantidad;

        calcularTotalCreateItem();
    });

    btnDecrementarCreate.addEventListener("click", function() {
        // Obtener el valor actual del input y convertirlo a un número
        let cantidad = parseInt(cantidadInputCreate.value);

        // Incrementar la cantidad
        if (cantidad >= 1) {
            cantidad -= 1;
        } else {
            cantidad = 1;
        }


        // Actualizar el valor del input
        cantidadInputCreate.value = cantidad;
        calcularTotalCreateItem();
    });

    inputDescuentoCreate.addEventListener('change', function() {
        calcularTotalCreateItem();
    });

    function handleChangeDescuentoCreate() {
        if (descuentoPorcentajeCreate.checked) {
            // Si se selecciona el descuento porcentaje, habilitar el inputDescuento
            inputDescuentoCreate.disabled = false;
            inputDescuentoCreate.maxLength = 2; // Establecer el máximo a 2 dígitos
            inputDescuentoCreate.placeholder = 'Ingrese porcentaje (%)';
        } else if (descuentoValorFijoCreate.checked) {
            // Si se selecciona el descuento valor fijo, habilitar el inputDescuento
            inputDescuentoCreate.disabled = false;
            inputDescuentoCreate.maxLength =
                totalPrecioCompraInputEdit; // Establecer el máximo al valor almacenado en totalPrecioCompraInputEdit
            inputDescuentoCreate.placeholder = 'Ingrese valor fijo ($)';
        }
    }

    inputDescuentoCreate.addEventListener('change', function() {
        calcularTotalCreateItem
    (); // Llamar a la función calcularTotalEditItem cuando cambie el valor del inputDescuento
    });

    function calcularTotalCreateItem() {
        const cantidad = parseFloat(cantidadInputCreate.value);
        const precio = parseFloat(precioUnitarioInputCreate.value);
        const descuento = parseFloat(inputDescuentoCreate.value);

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
            ivaInputCreate.value = Math.round(ivaUnitario * cantidad);

            // Actualizar el input de total con el descuento aplicado
            totalPrecioCompraInputCreate.value = total.toFixed(0);
        } else {
            spanTotal.textContent = "Error"; //
        }
    }


    function obtenerPrecioSuperiorACero(producto) {
        var verificar = {
            precio_caja: producto.precio_caja,
            precio_blister: producto.precio_blister,
            precio_unidad: producto.precio_unidad
        };

        var preciosMayoresACero = Object.keys(verificar).filter(function(key) {
            return verificar[key] > 0;
        });

        if (preciosMayoresACero.length === 1) {
            return preciosMayoresACero[0];
        } else {
            return null;
        }
    }


    guardarItemVenta.addEventListener('click', function() {

        let item = {};

        item.cantidad = cantidadInputCreate.value;
        item.code = productoEditar.code;
        item.descuento = parseInt(totalDescontado);
        item.iva = parseInt(ivaInputEdit.value);
        item.key = generarKey();
        item.nombre = productoEditar.name;
        item.precio_unitario = parseInt(precioUnitarioInputCreate.value);
        item.producto_id = productoEditar.id;
        item.total = parseInt(totalPrecioCompraInputCreate.value);
        item.forma = selectPresentacionCreate.value;



        // Verificar si el producto ya existe
        const productoExistente = verificarExistencia(item.producto_id, item.forma);

        // Si el producto ya existe, emitir un mensaje de error
        if (productoExistente) {
            alert('pailas');
            return;
        } else {
            // Si el producto no existe, guardar el registro en el local storage
            let ordersPos = JSON.parse(localStorage.getItem('ordersPos')) || [];
            ordersPos.push(item);
            localStorage.setItem('ordersPos', JSON.stringify(ordersPos));
            console.log('Producto registrado correctamente.');
        }



        mostrarDatosLocalStorageEnTabla();

        cerrarModalYLimpiarCreate();

    });

    function generarKey() {
        const fechaHoraActual = new Date();
        const key =
            `${fechaHoraActual.getFullYear()}${fechaHoraActual.getMonth()}${fechaHoraActual.getDate()}${fechaHoraActual.getHours()}${fechaHoraActual.getMinutes()}${fechaHoraActual.getSeconds()}${fechaHoraActual.getMilliseconds()}`;
        return key;
    }

    function verificarExistencia(producto_id, forma) {
        const ordersPos = JSON.parse(localStorage.getItem('ordersPos')) || [];
        return ordersPos.some(item => item.producto_id === producto_id && item.forma === forma);
    }

    function cerrarModalYLimpiarCreate() {
        const modal = document.getElementById("addProductCode");

        modal.style.display = "none";


        ivaUnitario = 0;
        productoEditar = '';
        indiceItem = '';
        ivaInputCreate.value = '';
        precioUnitarioInputCreate.value = '';
        inputDescuentoCreate.value = '';
        selectPresentacionCreate = '';
        cantidadInputCreate = '';
        totalPrecioCompraInputCreate = '';




        // Puedes agregar más limpieza de variables aquí según sea necesario

    }

    cerrarModalBtnCreate.addEventListener('click', cerrarModalYLimpiarCreate);





    /*---------------------Hasta aqui la logica producto codigo de barras --------------------------------------*/


    /*---------------------------------Logica para proceso de cotizacion -----------------------*/

    inputCodigoCotizacion.addEventListener('keyup', function(event) {
        // Verificar si la tecla presionada es "Enter" (código de tecla 13)
        if (event.keyCode === 13) {
            // Obtener el valor del input
            const valorInput = inputCodigoCotizacion.value;

            // Emitir un evento Livewire con el valor como parámetro
            Livewire.emit('buscarCotizacion', valorInput);
        }
    });


    window.addEventListener('datos-cotizacion', function(event) { //Evento que trae los datos desde el backend
        // Capturas los datos pasados desde Laravel
        const data = event.detail.datos;
        const productos = data.productos;
        cliente.value = data.cliente;
        tipoOperacion.value = "VENTA";

        // Almacenas los datos en el localStorage
        localStorage.setItem('ordersPos', JSON.stringify(productos));

        mostrarDatosLocalStorageEnTabla();
    });

    /*------------------------------------Hasta aqui toda la logica de cotizacion -----------------*/


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

            var cantidadCell = row.insertCell();
            cantidadCell.textContent = order.cantidad;
            cantidadCell.classList.add('text-center'); // Alineación al centro


            // IVA formateado como moneda
            var ivaCell = row.insertCell();
            if (order.iva > 100) {
                var ivaSinDecimales = Math.round(order.iva).toFixed(0);
                ivaCell.textContent = formatCurrency(ivaSinDecimales);
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
            subtotal += order.precio_unitario * order.cantidad; // Calcular subtotal
            descuentoTotal += order.descuento; // Sumar descuentos
            ivaTotal += order.iva; // Sumar impuestos
        });

        // Calcular total sumando el subtotal, el impuesto y restando el descuento
        total = subtotal + ivaTotal - descuentoTotal;

        var ivaSinDecimales = Math.round(ivaTotal).toFixed(0);
        var descuentoSinDecimales = Math.round(descuentoTotal).toFixed(0);

        spanSubTotal.textContent = formatCurrency(subtotal);
        spanDescuento.textContent = formatCurrency(descuentoSinDecimales);
        spanIva.textContent = formatCurrency(ivaSinDecimales);
        spanTotal.textContent = formatCurrency(total);

        cambiarEstadoBotonPagar(total);

      // mostrarBotonDescuentoGlobal(total, descuentoTotal);

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

    window.addEventListener("error-busqueda", (event) => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No hay datos disponibles'
        });
    });


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
</script>

@section('js')
    <script>
        $(document).ready(function() {
            $(".js-example-basic-single").select2();

            // Escucha el evento emitido por Livewire cuando se agrega un nuevo cliente
            window.livewire.on("ClientEvent", function(client) {
                // Agrega el nuevo cliente al select2
                var option = new Option(client.name, client.id, true, true);
                $("#cliente").append(option).trigger("change");

                // Opcional: Puedes seleccionar automáticamente el nuevo cliente
                // $('#cliente').val(client.id).trigger('change');

                // Opcional: Puedes enfocar y abrir el select2 para que el usuario pueda ver el nuevo cliente
                // $('#cliente').select2('open');
            });
        });
    </script>

@stop


@section('css')
    <style>
        .select2-selection--single {
            height: 39px !important;
        }

        .select2-selection__arrow {
            top: 10px !important;
            right: 10px !important;
            /* Ajusta la posición vertical del ícono */
        }

        .align-right {
            text-align: right;
        }

        .align-center {
            text-align: center;
        }

        .btn-eliminar {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            font-size: 16px;
            color: #888;
            /* Color normal del icono */
            transition: color 0.3s;
            /* Transición de color para el efecto hover */
            display: inline-block;
            /* Hacer que el botón sea un bloque en línea para poder alinear a la derecha */

            /* Estilo de cursor al pasar el ratón sobre el botón */
            cursor: pointer;
        }

        .btn-eliminar:hover {
            color: #dc3545;
            /* Color del icono al pasar el ratón sobre el botón */
        }

        /* Alineación a la derecha y colocación al final de la celda */
        .celda-eliminar {
            text-align: right !important;
            /* Alinear el contenido a la derecha */
            white-space: nowrap;
            /* Evitar que el contenido se divida en varias líneas */
        }

        /* Estilo para el botón de pago si es relevante en tu formulario */
        .campo-pago button {
            padding: 8px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }


        /* Estilo para el nombre del método de pago */
        .metodo-pago span {
            font-size: 14px;
        }

        .ocultar-columna-id {
            display: none;
        }

        .table-responsive-md {
            max-height: 400px;
            /* Establece la altura máxima que prefieras */
            overflow-y: auto;
            /* Añade un scrollbar vertical cuando sea necesario */
        }

        /* Estilo del botón de pago */
        button.btn-pagar {
            background-color: #4CAF50;
            /* Color de fondo */
            color: #ffffff;
            /* Color del texto */
            border: none;
            /* Sin borde */
            border-radius: 5px;
            /* Esquinas redondeadas */
            padding: 10px 20px;
            /* Relleno interno */
            font-size: 16px;
            /* Tamaño del texto */
            cursor: pointer;
            /* Cambia el cursor al pasar sobre el botón */
            transition: background-color 0.3s ease;
            /* Animación de transición suave */
        }

        /* Cambia el color de fondo cuando el mouse está sobre el botón */
        button.btn-pagar:hover {
            background-color: #45a049;
        }

        /* Agrega estilos generales a los elementos de clase costo-compra */
        .costo-compra {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Estilo específico para la etiqueta (label) */
        .costo-compra label {
            font-weight: bold;
            margin-right: 10px;
        }

        /* Estilo específico para el span */
        .costo-compra span {
            font-size: 16px;
            /* Ajusta el tamaño de fuente según tus preferencias */
        }

        /* Estilo específico para el elemento total */
        .costo-compra.total label,
        .costo-compra.total span {
            font-size: 30px;
            font-weight: bold;
        }

        /* Alineación del texto en el elemento total */
        .costo-compra.total {
            text-align: right;
        }
    </style>
@stop
