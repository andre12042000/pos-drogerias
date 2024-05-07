<div>
    @include('popper::assets')
    @section('title', 'Orden De Trabajo')

    <div class="py-2">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary float-end"><i
                class="bi bi-arrow-left-circle"></i> Atrás</a>

    </div>
    <div class="card-body mt-4">
        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    @if ($status == 1)
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon bg-success">
                                ABIERTO
                            </div>
                        </div>
                    @else
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon bg-dark">
                                CERRADO
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
                                <p class="text-bold">{{ $full_numero }}</p>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label" for="nombre"><strong>Cliente</strong></label>
                                <p>{{ ucwords($client_name) }}</p>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label" for="num_compra"><strong>Fecha</strong></label>
                                <p> {{ \Carbon\Carbon::parse($fecha)->format('d M Y') }} </p>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label" for="num_compra"><strong>Asignado a</strong></label>
                                <p>
                                    {{ $asignado }} <button class="btn btn-outlife-success"
                                        wire:click="sendOrden( {{ $order }} )"><i
                                            class="bi bi-plus-circle text-success " style="cursor: pointer"
                                            data-toggle="modal" data-target="#asignadomodal"
                                            title="Actualizar asignación"></i></button>
                                </p>
                            </li>


                            @if (!empty($descripcion))
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label mr-3" for="num_compra"><strong>Descripción</strong>
                                        <br></label>
                                    <p>{{ $descripcion }}</p>
                                </li>
                            @endif

                        </ul>

                        <hr>
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapseOne" aria-expanded="false"
                                        aria-controls="flush-collapseOne">
                                        Historial de asignación
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse"
                                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            @if (count($historiales) < 1)
                                                No hay asignaciones aun!
                                            @else
                                                @foreach ($historiales as $tecnico)
                                                    <ol class="list-group ">
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-start">
                                                            <div class="ms-2 me-auto">
                                                                <div class="fw-bold"><i class="fas fa-user-cog"></i>
                                                                    {{ $tecnico->user->name }}</div>

                                                            </div>
                                                            <span
                                                                class="badge bg-primary rounded-pill">{{ \Carbon\Carbon::parse($tecnico->created_at)->diffForHumans() }}
                                                            </span>
                                                        </li>
                                                    </ol>
                                                @endforeach
                                            @endif

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <ul class="mt-5">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label" for="nombre"><strong>TOTAL</strong></label>
                                <p class="text-bold">$ {{ number_format($valor, 0) }}</p>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <label class="form-control-label" for="nombre"><strong>ABONADO</strong></label>
                                <p class="text-bold">$ {{ number_format($abono, 0) }}</p>
                            </li>

                            <li
                                class="list-group-item d-flex justify-content-between align-items-center @if ($saldo > 0) list-group-item-danger
                        @else
                            list-group-item-info @endif">
                                <label class="form-control-label" for="nombre"><strong>SALDO</strong></label>
                                <p class="text-bold">$ {{ number_format($saldo, 0) }}</p>
                            </li>
                        </ul>


                        <ul class="mt-5">
                            <div class="d-grid gap-2">
                                <button
                                    class="btn btn-outline-dark float-right @if ($status == 2 || $saldo != 0) disabled @endif"
                                    wire:click = 'cerrar_orden'> Cerrar Orden</button>

                            </div>
                        </ul>



                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center"><strong>Detalles</strong></h4>
                    </div>

                    @if ($details->count() > 0)
                        <div class="card-footer">


                            <table id="detalles" class="table">
                                <thead>
                                    <tr>

                                        <th class="text-center">Producto</th>
                                        <th class="text-right">Precio Unitario</th>
                                        <th class="text-right">Cantidad</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($details as $detalle)
                                        <tr>
                                            <td>{{ $detalle->product->name }}</td>

                                            <td class="text-center">$ {{ number_format($detalle->price, 0) }}</td>
                                            <td class="text-center">{{ $detalle->quantity }}</td>
                                            <td class="text-center">$ {{ $detalle->price * $detalle->quantity }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif


                    @if ($detail_abonos->count() > 0)
                        <div class="card-footer">
                            <div class="card-header">
                                <div class="row">
                                    <p class="text-bold col-10"> Historial de Abonos </p>
                                    <button
                                        class="btn btn-outline-dark float-right col-2 @if ($status == 2) disabled @endif"
                                        data-toggle="modal" data-target="#abonomodal"
                                        wire:click="sendDataAbono({{ $order }})">Abonar <i
                                            class="las la-plus-circle"></i></button>
                                </div>
                            </div>

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
                                <tbody>
                                    @foreach ($detail_abonos as $abono)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($abono->created_at)->format('d M
                                                                                                                                                                                                                                                                                                                                        Y') }}
                                            </td>
                                            <td class="text-center">{{ $abono->full_nro }}</td>
                                            <td class="text-center">{{ $abono->metodopago }}</td>
                                            <td class="text-center">{{ ucwords($abono->user->name) }}</td>
                                            <td class="text-right"> $ {{ number_format($abono->amount, 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="card">
                    <div class="card-header text-center">
                        <h4>Añadir productos</h4>
                    </div>

                    <div class="row mt-2">

                        <div class="col ml-2 mr-2">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <i class="bi bi-search mr-3 mt-2" style="cursor: pointer" data-toggle="modal"
                                        data-target="#searchproduct"></i>
                                </div>
                                <input type="text"
                                    class="form-control @if ($error_search) is-invalid @endif"
                                    id="inlineFormInputGroup" placeholder="Buscar producto por código"
                                    wire:model.lazy="codigo_de_producto" wire:keydown.enter="searchProductCode"
                                    autocomplete="disabled">
                                <!--Buscador por código -->
                            </div>
                            @error('codigo_de_producto')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>

                    <div class="table-responsive-md">
                        <table class="table" id="tablaProductos">
                            <thead>
                                <tr>

                                    <th scope="col">Código</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Forma</th>
                                    <th scope="col" class="text-end">Precio Unit.</th>
                                    <th scope="col" class="text-center">Cant.</th>
                                    <th scope="col" class="text-end">Iva</th>
                                    <th scope="col" class="text-end">Total</th>

                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            @livewire('orders.comentario-component', ['order' => $id_order, 'status' => $status])
        </div>
    </div>


    @include('modals.products.search')
    @include('modals.orders.updateasig')


    @include('modals.abono.abono')
    <script>
        /*--------------------Asignación de variables a objetos html ---------------*/
        var ivaUnitario = 0; //Usado en el modal de editar Item
        var totalDescontado = 0;
        let indiceItem;
        let productoEditar;


        const buscarProductoCodigo = document.getElementById('buscarProductoCodigo');

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

        function deshabilitarOpcionesSelect(precioUnico) {
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
                alert('No es posible añadir este producto, ya ha sido agregado a la lista anteriormente');
                cerrarModalYLimpiarCreate();
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




        function mostrarDatosLocalStorageEnTabla() {
            var orders = JSON.parse(localStorage.getItem('ordersPos')) || [];
            var tablaBody = document.querySelector('#tablaProductos tbody');

            // Limpiar tabla antes de agregar datos
            tablaBody.innerHTML = '';

            orders.forEach(function(order, index) {
                var row = tablaBody.insertRow(); // Insertar una nueva fila en la tabla

                // Insertar celdas en la fila

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
            subtotal = total - (ivaTotal - descuentoTotal);

            var subTotalSinDecimales = Math.round(subtotal).toFixed(0);
            var ivaSinDecimales = Math.round(ivaTotal).toFixed(0);
            var descuentoSinDecimales = Math.round(descuentoTotal).toFixed(0);
            var totalSinDecimales = Math.round(total).toFixed(0);

            spanSubTotal.textContent = formatCurrency(subTotalSinDecimales);
            spanDescuento.textContent = formatCurrency(descuentoSinDecimales);
            spanIva.textContent = formatCurrency(ivaSinDecimales);
            spanTotal.textContent = formatCurrency(totalSinDecimales);

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

            // const radios = document.querySelectorAll('input[name="opcionRadio"]');
            let imprimir;
            // Iterar sobre los radios para verificar cuál está seleccionado

            var radioSi = document.getElementById('opcionSi');
            var radioNo = document.getElementById('opcionNo');

            if (radioSi.checked) {
                imprimir = radioSi.value;
            } else if (radioNo.checked) {
                imprimir = radioNo.value;
            }

            /*  radios.forEach(radio => {
                 // Obtener el valor del radio seleccionado
                 imprimir = radio.value;
             }); */

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
</div>
