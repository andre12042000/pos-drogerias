<div>
    @include('popper::assets')
    @section('title', 'Orden De Trabajo')

    <div class="py-2">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary float-end"><i
                class="bi bi-arrow-left-circle"></i> Atrás</a>

    </div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show t:0.2 mt-5 " role="alert">
            <i class="bi bi-exclamation-triangle bold"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        {{ session()->forget('success') }}
    @endif

    @if (session()->has('warning'))
        <div class="alert alert-warning alert-dismissible fade show t:0.2 mt-5 " role="alert">
            <i class="bi bi-exclamation-triangle bold"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        {{ session()->forget('warning') }}
    @endif

    @if (session()->has('danger'))
        <div class="alert alert-danger alert-dismissible fade show t:0.2 mt-5 " role="alert">
            <i class="bi bi-exclamation-triangle bold"></i> {{ session('danger') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        {{ session()->forget('danger') }}
    @endif
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

                            <li class="list-group-item  justify-content-between align-items-center">
<strong>Asignación de operario</strong>
<div class="row">
    <div class="col-10">


        <select @if($orden_asignada == 1)disabled @endif class="form-select  @error('asignado') is-invalid @elseif($asignado != '') is-valid @enderror" aria-label="Default select example" wire:model="asignado">
            <option value="">Seleccionar Operario</option>
            @foreach ($tecnicos as $tecnico)
                <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
            @endforeach
          </select>
          @error('asignado')
          <span class="text-danger">{{ $message }}</span>
      @enderror
    </div>
    <div class="col-2">
        @if ($orden_asignada != 1)
        <button wire:click="actualizarasignacion" title="Asiganar"  class="btn btn-outline-success @if ($tecnicos->isEmpty()) disabled @endif"><i class="bi bi-arrow-clockwise"></i></button>
        @else
        <button wire:click="eliminarasignacion" title="Eliminar asignación" class="btn btn-outline-danger"><i class="bi bi-trash3-fill"></i></button>
        @endif

    </div>

</div>








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

                            <br>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center @if ($saldo > 0) list-group-item-danger
                        @else
                            list-group-item-info @endif">
                                <label class="form-control-label" for="nombre"><strong>SALDO</strong></label>
                                <p class="text-bold">$ {{ number_format($saldo, 0) }}</p>
                            </li>
                            @if ($saldo < 0)
                                <strong>Nota: </strong> <span>Tienes un saldo a favor de</span> $
                                {{ number_format(abs($saldo), 0) }}
                            @endif
                        </ul>


                        <ul class="mt-5">
                            <div class="d-grid gap-2">
                                <button
                                    class="btn btn-outline-dark float-right @if ($status == 2 || $saldo > 0) disabled @endif"
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
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($details as $detalle)
                                        <tr>
                                            <td>{{ $detalle->product->name }}</td>

                                            <td class="text-center">$ {{ number_format($detalle->price, 0) }}</td>
                                            <td class="text-center">{{ $detalle->quantity }}</td>
                                            <td class="text-center">$ {{ $detalle->price * $detalle->quantity }}</td>
                                            <td><a style="cursor: pointer"
                                                    wire:click="eliminarproducto({{ $detalle->id }})"><i
                                                        class=" text-darkbi bi-trash3-fill"></i></a></td>

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
                                        data-target="#basicsearchproduct"></i>
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
                                    <th scope="col" class="text-end">Descuento</th>
                                    <th scope="col" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                            <tr class="mt-3">

                                <th colspan="2">Descuento: <span class="descuento float-end text-end"></span></th>
                                <th colspan="2">IVA: <span class="iva float-end text-end"></span></th>
                                <th colspan="2"> Subtotal:<span class="subtotal float-end text-end"></span></th>
                                <th colspan="2">Total: <span class="total float-end text-end"
                                        style=" font-weight: bold;"></span></th>



                            </tr>
                        </table>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-outline-success float-end" id="miBoton">Actualizar Productos</button>
                    </div>
                </div>
            </div>
            @livewire('orders.comentario-component', ['order' => $id_order, 'status' => $status])
        </div>

    </div>


    @include('modals.products.basicbusqueda')



    <script>
        document.getElementById('miBoton').addEventListener('click', function() {
            ejecutarProcesoGuardado();
        });
        /*--------------------Asignación de variables a objetos html ---------------*/
        var ivaUnitario = 0; //Usado en el modal de editar Item
        var totalDescontado = 0;
        let indiceItem;
        let productoEditar;
        const tipoOperacion = document.getElementById('tipoOperacion');
        const botonEjecuta = document.getElementById('pagarBtns');
        const inputCodigoCotizacion = document.getElementById('codigo_cotizacion');
        const cliente = document.getElementById('cliente');
        const mensajeErrorCliente = document.getElementById('cambiarClienteHelp');
        const mensajeErrorMetodoPagoHelp = document.getElementById('cambiarMetodoPagoHelp');

        const radioSi = document.getElementById('opcionSi');
        const radioNo = document.getElementById('opcionNo');

        const buscarProductoCodigo = document.getElementById('buscarProductoCodigo');

        const MetodoPago = document.getElementById('selectMetodoPago');
        const CantidadPagada = document.getElementById('inputCantidadPagada');
        const inputCambio = document.getElementById('inputCambio');

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

        function calcularVueltos() {
            const totalSpan = document.querySelector('.total');
            const totalTexto = totalSpan.textContent;

            // Eliminar el símbolo de la moneda ($) y los separadores de miles (.)
            const totalLimpio = totalTexto.replace(/[$.]/g, '');

            // Convertir a número entero
            const totalEntero = parseInt(totalLimpio);

            const inputCantidadPagada = document.getElementById('inputCantidadPagada');
            const inputCambio = document.getElementById('inputCambio');

            if (!isNaN(totalEntero)) {
                // Obtener la cantidad pagada
                let cantidadPagada = parseFloat(inputCantidadPagada.value);

                // Verificar si la cantidad pagada es válida y mayor que cero
                if (!isNaN(cantidadPagada) && cantidadPagada > 0) {
                    let cambio = cantidadPagada - totalEntero;

                    // Mostrar el cambio solo si es mayor o igual a cero
                    if (cambio >= 0) {
                        inputCambio.value = cambio.toFixed(0); // Mostrar el cambio sin decimales
                    } else {
                        inputCambio.value = "La cantidad pagada es insuficiente";
                    }
                } else {
                    inputCambio.value = "Ingrese una cantidad válida";
                }
            } else {
                inputCambio.value = "No se pudo calcular el cambio";
            }
        }

        function cambioCliente() {
            if (cliente.value > 1) {
                cambiarClienteHelp.style.display = 'none';
            }
        }


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
                    let mensaje = 'El producto no existe';
                    mostrarError(mensaje);
                }

                limpiarSearchCode();

            }
        });

        window.addEventListener('seleccionarProductoEvent', function(event) { //Evento que trae los datos desde el backend

            // Mostrar el modal
            const modal = document.getElementById("addProductCode");
            const modalContent = document.getElementById("modalContent");
            modal.style.display = "block";

            productoEditar = event.detail;

            precioUnitarioInputCreate.value = productoEditar.precio_caja;
            totalPrecioCompraInputCreate.value = productoEditar.precio_caja;
            deshabilitarOpcionesSelect();
        });

        function deshabilitarOpcionesSelect() {
            const opciones = selectPresentacionCreate.options;

            for (let i = 0; i < opciones.length; i++) {
                if (opciones[i].value === 'disponible_caja' && productoEditar.disponible_caja === 0) {
                    opciones[i].disabled = true;
                }

                if (opciones[i].value === 'disponible_blister' && productoEditar.disponible_blister === 0) {
                    opciones[i].disabled = true;
                }

                if (opciones[i].value === 'disponible_unidad' && productoEditar.disponible_unidad === 0) {
                    opciones[i].disabled = true;
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

        precioUnitarioInputCreate.addEventListener('blur', function() {
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
            calcularTotalCreateItem(); // Llamar a la función calcularTotalEditItem cuand
        });

        function cambiarModoPresentacion() {
            console.log(productoEditar);
            let presentacion = selectPresentacionCreate.value;

            if (presentacion === 'disponible_caja') {
                precioUnitarioInputCreate.value = productoEditar.precio_caja;
            } else if (presentacion === 'disponible_blister') {
                precioUnitarioInputCreate.value = productoEditar.precio_blister;
            } else {
                precioUnitarioInputCreate.value = productoEditar.precio_unidad;
            }
            calcularTotalCreateItem();

        }

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



        guardarItemVenta.addEventListener('click', function() {

            let item = {};

            if (selectPresentacionCreate.value === 'disponible_caja') {
                let iva = productoEditar.valor_iva_caja * cantidadInputCreate.value;
            } else if (selectPresentacionCreate.value === 'disponible_blister') {
                let iva = productoEditar.valor_iva_blister * cantidadInputCreate.value;
            } else {
                let iva = productoEditar.valor_iva_unidad * cantidadInputCreate.value;
            }

            if (iva == null || iva == isNaN) {
                iva = 0;
            }

            item.cantidad = cantidadInputCreate.value;
            item.code = productoEditar.code;
            item.descuento = parseInt(totalDescontado);
            item.iva = parseInt(iva);
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
                mostrarError('No es posible añadir este producto, ya ha sido agregado a la lista anteriormente');
                cerrarModalYLimpiarCreate();
                return;
            } else {
                // Si el producto no existe, guardar el registro en el local storage
                let ordersPos = JSON.parse(localStorage.getItem('orderstrabajoPos')) || [];
                ordersPos.push(item);
                localStorage.setItem('orderstrabajoPos', JSON.stringify(ordersPos));
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
            const ordersPos = JSON.parse(localStorage.getItem('orderstrabajoPos')) || [];
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

            limpiarSearchCode();
            mostrarDatosLocalStorageEnTabla();

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
            localStorage.setItem('orderstrabajoPos', JSON.stringify(productos));

            mostrarDatosLocalStorageEnTabla();
        });

        /*------------------------------------Hasta aqui toda la logica de cotizacion -----------------*/



        botonEjecuta.addEventListener('click', function() {
            // Código a ejecutar cuando se haga clic en el botón
            console.log('holamundo');
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
            var btnPagar = botonEjecuta;

            if ($value > 0) {
                btnPagar.disabled = false;
            } else {
                btnPagar.disabled = true;
            }

        }


        function actualizarBoton() {
            var Total = spanTotal.textContent;
            const indiceSeleccionado = tipoOperacion.value;
            var btnPagar = botonEjecuta;
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
                    cambiarOpcionImprimir("1");
                    if (cliente.value === '1') {
                        cambiarClienteHelp.style.display = 'block';
                    } else {
                        cambiarClienteHelp.style.display = 'none';
                    }
                    break;
                case 'CONSUMO_INTERNO':
                    textoBtn = 'GUARDAR CONSUMO INTERNO';
                    cambiarOpcionImprimir("1");
                    cambiarClienteHelp.style.display = 'none';
                    break;
                case 'COTIZACION':
                    textoBtn = 'REALIZAR COTIZACIÓN';
                    cambiarOpcionImprimir("1");
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

        function cambiarOpcionImprimir(activar) {

            if (activar === '1') {
                radioSi.checked = true;
            } else {
                radioNo.checked = true;
            }

        }

        function mostrarDatosLocalStorageEnTabla() {
            var orders = JSON.parse(localStorage.getItem('orderstrabajoPos')) || [];
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
                ivaCell.textContent = formatCurrency(order.iva);
                ivaCell.classList.add('align-right'); // Alinear a la derecha


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
            var orders = JSON.parse(localStorage.getItem('orderstrabajoPos')) || [];

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
            subtotal = total - (ivaTotal);

            if (descuentoTotal > 0) {
                total = total - descuentoTotal;
            }


            spanSubTotal.textContent = formatCurrency(subtotal);
            spanDescuento.textContent = formatCurrency(descuentoTotal);
            spanIva.textContent = formatCurrency(ivaTotal);
            spanTotal.textContent = formatCurrency(total);



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
            let orders = JSON.parse(localStorage.getItem("orderstrabajoPos")) || [];

            // Verificar si el índice está dentro del rango de la matriz
            if (index >= 0 && index < orders.length) {
                // Eliminar el elemento en el índice dado
                orders.splice(index, 1);
                // Actualizar el localStorage con los datos actualizados
                localStorage.setItem("orderstrabajoPos", JSON.stringify(orders));
                // Luego, puedes actualizar la tabla o cualquier otra cosa que necesites hacer después de eliminar la orden.
            } else {
                console.error("Índice fuera de rango.");
            }

            mostrarDatosLocalStorageEnTabla();


        }

        /*------------------------------------------Proceso guardado de datos --------------------------------------*/

        function ejecutarProcesoGuardado() {


            const orderPosData = localStorage.getItem('orderstrabajoPos');
            if (orderPosData) {
                // Convertir los datos de formato JSON a un array de JavaScript
                var orderPosArray = JSON.parse(orderPosData);
            } else {
                let mensaje = 'Ops!, ocurrio un error con la data, comuniquese con el administrador del sistema';
                mostrarError(mensaje);
            }



            let totales = calcularTotales();

            const datos = {

                'productos': orderPosArray,
                'totales': totales,
            };

            Livewire.emit('guardardetallesordenEvent', datos);

            limpiartabla();
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
            let ordersPos = JSON.parse(localStorage.getItem('orderstrabajoPos')) || [];

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
            item_actualizar.iva = ivaUnitario * cantidadInputEdit.value;


            ordersPos[indiceItem] = item_actualizar;

            // Actualizar el localStorage con el array actualizado ordersPos
            localStorage.setItem('orderstrabajoPos', JSON.stringify(ordersPos));

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

        function limpiarSearchCode() {
            const inputBuscarProductoCodigo = document.getElementById('buscarProductoCodigo');
            inputBuscarProductoCodigo.value = '';
        }

        cerrarModalBtn.addEventListener('click', cerrarModalYLimpiar);



        document.querySelectorAll('.fila-datos').forEach(function(row) {
            row.addEventListener('dblclick', function() {
                var index = this.rowIndex - 1; // Restar 1 para obtener el índice correcto
                var orders = JSON.parse(localStorage.getItem('orderstrabajoPos')) || [];
                var order = orders[index];
                mostrarModalEditarItem(order);
            });
        });



        // Llamar a la función para mostrar los datos del localStorage en la tabla cuando se cargue la página
        mostrarDatosLocalStorageEnTabla();

        window.addEventListener('addProductLocalStorageDesdeCode', function(event) { //Traigo el mensaje desde livewire
            const dataProduct = event.detail.dataProduct;
            console.log(dataProduct);
            let ordersPos = JSON.parse(localStorage.getItem('orderstrabajoPos')) || [];

            let existingProductIndex = ordersPos.findIndex(product => product.producto_id === dataProduct
                .producto_id);

            if (existingProductIndex !== -1) {
                // Si el producto ya existe, actualiza la cantidad y el total
                ordersPos[existingProductIndex].total += dataProduct.total;
                ordersPos[existingProductIndex].iva += dataProduct.iva;
                ordersPos[existingProductIndex].cantidad += 1;
            } else {
                // Si es un producto nuevo, agrégalo al local storage
                ordersPos.push(dataProduct);
            }

            localStorage.setItem('orderstrabajoPos', JSON.stringify(ordersPos));
            limpiarSearchCode();
            mostrarDatosLocalStorageEnTabla();

        });



        /*------------------------------------------------Alertas sweet Alert ---------------------------------*/
        function mostrarError(mensaje) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: mensaje
            });
        }

        window.addEventListener('mostrarErrorLivewire', function(event) { //Traigo el mensaje desde livewire
            const mensaje = event.detail.mensaje;
            mostrarError(mensaje);
            limpiarSearchCode();

        });

        window.addEventListener("error-busqueda", (event) => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No hay datos disponibles'
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('procesoGuardadoCompleto', function() {
                window.dispatchEvent(new CustomEvent('proceso-guardado-completo'));
            });
        });
        limpiarLocalStorage();

        window.addEventListener("proceso-guardado-completo", (event) => {

            let orders = JSON.parse(localStorage.getItem("orderstrabajoPos")) || [];

            // Filtrar los pedidos que quieres mantener en un nuevo array
            let filteredOrders = orders.filter(order => {
                return false; // Esto elimina todos los pedidos
            });

            // Actualizar el localStorage con los datos filtrados
            localStorage.setItem("orderstrabajoPos", JSON.stringify(filteredOrders));

        });
    </script>


        <script>




function limpiarLocalStorage() {
        let orders = JSON.parse(localStorage.getItem("ordersPos")) || [];

        // Filtrar los pedidos que quieres mantener en un nuevo array
        let filteredOrders = orders.filter(order => {
            return false; // Esto elimina todos los pedidos
        });

        // Actualizar el localStorage con los datos filtrados
        localStorage.setItem("ordersPos", JSON.stringify(filteredOrders));
    }






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

            function limpiartabla() {
                localStorage.removeItem('orderstrabajoPos');
            }
        </script>




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
</div>
@include('modals.abono.abono')
