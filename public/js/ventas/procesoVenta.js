var productosParaVenta = [];
var productoActual;
let granTotal = 0;
let subTotalGlobal = 0;
let ivaTotalGlobal = 0;
let descuentoGlobal = 0;

class ProductManager {
    constructor() {
        const granTotalFormateado = granTotal;
        const granIvaFormateado = ivaTotalGlobal;
        const subTotalGlobalFormateado = subTotalGlobal; //
        const subdescuentoGlobalFormateado = descuentoGlobal;

        // Asignar el valor al elemento span con la clase granTotal
        document.querySelector(".granTotal").textContent = granTotalFormateado;
        document.querySelector(".ivaTotalGlobal").textContent =
            granIvaFormateado;
        document.querySelector(".subTotalGlobal").textContent =
            subTotalGlobalFormateado;
        document.querySelector(".descuentoGlobal").textContent =
            subdescuentoGlobalFormateado;

        this.productosParaVenta = [];
        this.productoActual;
        this.cantidadModal = new bootstrap.Modal(
            document.getElementById("cantidadModal")
        );
        this.selectPresentacion = document.getElementById("selectPresentacion");
        this.totalPrecioCompraInput = document.getElementById(
            "totalPrecioCompraInput"
        );
        this.precioUnitarioInput = document.getElementById(
            "precioUnitarioInput"
        );
        this.cantidadInput = document.getElementById("cantidadInput");

        this.inputCantidadPagada = document.getElementById(
            "inputCantidadPagada"
        );
        inputCantidadPagada.addEventListener("input", () =>
            this.handleCantidadPagadaInputChange()
        );

        const cantidadInput = document.getElementById("cantidadInput");
        cantidadInput.addEventListener("input", () =>
            this.handleCantidadInputChange()
        );

        const precioUnitarioInput = document.getElementById(
            "precioUnitarioInput"
        );
        precioUnitarioInput.addEventListener("input", () =>
            this.handlePrecioUnitarioInputChange()
        );

        const selectPresentacion =
            document.getElementById("selectPresentacion");
        selectPresentacion.addEventListener("change", () =>
            this.handlePresentacionChange()
        );

        const agregarBtn = document.getElementById(
            "agregarProductosArrayVenta"
        );
        agregarBtn.addEventListener("click", () =>
            this.handleAgregarButtonClick()
        );

        const pagarBtn = document.getElementById("pagarBtn");
        pagarBtn.addEventListener("click", () => this.handlePagarButtonClick());

        var valorSeleccionado;

        this.initializeListeners();
    }

    initializeListeners() {
        window.addEventListener("agregarProductoAlArrayCode", (event) =>
            this.handleAgregarProducto(event)
        );
    }

    handlePagarButtonClick() {

            // Obtener los elementos de los radio buttons
            const opcionSi = document.getElementById('opcionSi');
            const opcionNo = document.getElementById('opcionNo');

            // Obtener el valor seleccionado
            const opcionSeleccionada = document.querySelector('input[name="opcionRadio"]:checked').value;

            const selectMetodoPago = document.getElementById('selectMetodoPago');
            const metodoPagoSeleccionado = selectMetodoPago.value;

            const descuentoGlobalSpan = document.querySelector('.descuentoGlobal');
            const subTotalGlobalSpan = document.querySelector('.subTotalGlobal');
            const ivaTotalGlobalSpan = document.querySelector('.ivaTotalGlobal');
            const granTotalSpan = document.querySelector('.granTotal');

            // Obtener los valores de los elementos span
            const descuentoGlobal = parseFloat(descuentoGlobalSpan.textContent);
            const subTotalGlobal = parseFloat(subTotalGlobalSpan.textContent);
            const ivaTotalGlobal = parseFloat(ivaTotalGlobalSpan.textContent);
            const granTotal = parseFloat(granTotalSpan.textContent);








        // Crear un objeto con los datos
        const datosVenta = {
            granTotal: granTotal,
            subTotalGlobal: subTotalGlobal,
            ivaTotalGlobal: ivaTotalGlobal,
            descuentoGlobal: descuentoGlobal,
            imprimirRecibo: opcionSeleccionada,
            metodoPago: metodoPagoSeleccionado,
            productosParaVenta: productosParaVenta,
        };


        // Emitir los datos al componente Livewire
         Livewire.emit("crearVentaEvent", datosVenta);
    }

    handleAgregarButtonClick() {
        // Obtener la información del producto del modal (puedes acceder a esto según tu implementación específica)
        const producto = this.cantidadModal.producto;
        const cantidadIngresada = parseInt(this.cantidadInput.value);

        // Validar que la cantidad sea mayor que 0
        if (cantidadIngresada <= 0 || isNaN(cantidadIngresada)) {
            console.log("Ingrese una cantidad válida mayor que 0.");
            return;
        }

        // Validar que se haya seleccionado una presentación
        const presentacionSeleccionada = this.selectPresentacion.value;
        if (!presentacionSeleccionada) {
            console.log("Seleccione una presentación.");
            return;
        }

        // Validar que el precio unitario sea mayor que 0
        const precioUnitario = parseFloat(this.precioUnitarioInput.value);
        if (precioUnitario <= 0 || isNaN(precioUnitario)) {
            console.log("Ingrese un precio unitario válido mayor que 0.");
            return;
        }

        // Validar que el subtotal sea mayor que 0
        const subtotal = parseFloat(this.totalPrecioCompraInput.value);
        if (subtotal <= 0 || isNaN(subtotal)) {
            console.log("Ingrese un subtotal válido mayor que 0.");
            return;
        }

        this.handleActualizarOAgregarProducto(
            producto,
            presentacionSeleccionada,
            precioUnitario,
            cantidadIngresada
        );

        this.cantidadModal.hide();
    }

    handleLimpiarFormatoMoneda = (valorMoneda) => {
        // Eliminar cualquier carácter no numérico o símbolo de moneda
        const valorNumerico = valorMoneda.replace(/[^\d.-]/g, "");
        // Convertir a número
        return parseFloat(valorNumerico);
    };

    handlePrecioUnitarioInputChange() {
        const nuevoPrecioUnitario = parseFloat(
            document.getElementById("precioUnitarioInput").value
        );
        const cantidadIngresada = parseInt(
            document.getElementById("cantidadInput").value,
            10
        );

        // Verifica si la cantidadIngresada es un número válido antes de continuar
        if (isNaN(cantidadIngresada)) {
            console.log(
                "Ingrese una cantidad válida antes de cambiar el precio unitario."
            );
            return;
        }

        this.displayPrecioUnitarioYTotal(
            nuevoPrecioUnitario,
            cantidadIngresada
        );
    }

    handleCantidadInputChange() {
        console.log("cambie cantidad");

        // Obtener el valor de cantidadInput
        const nuevoValor = parseInt(
            document.getElementById("cantidadInput").value
        );

        // Verificar si se ha seleccionado una presentación
        const presentacionSeleccionada = this.selectPresentacion.value;

        if (!presentacionSeleccionada) {
            // Si no se ha seleccionado una presentación, mostrar un mensaje o realizar la lógica correspondiente
            console.log(
                "Seleccione una presentación antes de ingresar la cantidad."
            );
            return;
        }

        // Verificar si hay un valor en el precio unitario
        const precioUnitarioInputValue = this.precioUnitarioInput.value;

        if (!precioUnitarioInputValue) {
            // Si no hay un valor en el precio unitario, mostrar un mensaje o realizar la lógica correspondiente
            console.log(
                "Ingrese un precio unitario antes de ingresar la cantidad."
            );
            return;
        }

        // Realizar el cálculo de cantidad y actualizar el total
        this.displayPrecioUnitarioYTotal(precioUnitarioInputValue, nuevoValor);
    }

    handlePresentacionChange() {
        const producto = productoActual;
        // Lógica a ejecutar cuando cambia el valor de selectPresentacion
        const nuevoValor = this.selectPresentacion.value;

        // Verificar si hay un producto actualmente seleccionado
        if (!producto) {
            console.log(
                "Seleccione un producto antes de cambiar la presentación."
            );
            return;
        }

        // Obtener el precio unitario según la nueva presentación seleccionada
        const precioUnitario = this.getPrecioUnitario(producto, nuevoValor);

        // Obtener la cantidad actualmente ingresada
        const cantidadIngresada = parseInt(
            document.getElementById("cantidadInput").value
        );

        // Calcular y mostrar el precio unitario y el total
        this.displayPrecioUnitarioYTotal(precioUnitario, cantidadIngresada);
    }

    handleActualizarOAgregarProducto(
        producto,
        presentacionSeleccionada,
        precioUnitario,
        cantidadIngresada
    ) {
        // Buscar el producto en productosParaVenta
        const productoExistente = productosParaVenta.find(
            (item) =>
                item.id_producto === producto.id &&
                item.forma === presentacionSeleccionada
        );

        if (productoExistente) {
            // El producto ya existe, actualizar cantidad y subtotal
            productoExistente.cantidad += cantidadIngresada;
            productoExistente.subtotal =
                productoExistente.cantidad * precioUnitario;
        } else {
            // El producto no existe, agregar un nuevo producto
            const nuevoProducto = {
                item: productosParaVenta.length + 1,
                id_producto: producto.id,
                codigo: producto.code,
                descripcion: producto.name,
                forma: presentacionSeleccionada,
                precio_unitario: precioUnitario,
                cantidad: cantidadIngresada,
                iva: 0,
                descuento: 0,
                subtotal: cantidadIngresada * precioUnitario,
            };

            // Agregar el nuevo producto al array
            productosParaVenta.push(nuevoProducto);
        }

        this.reinicializarVariables();
        this.actualizarTabla();
    }

    handleAgregarProducto(event) {
        productoActual = event.detail.producto;
        const producto = event.detail.producto;

        if (event.detail.opcionSeleccionada != null) {
            const cantidadIngresada = 1;
            const presentacionSeleccionada = event.detail.opcionSeleccionada;
            const precioUnitario = this.getPrecioUnitario(
                producto,
                presentacionSeleccionada
            );

            if (precioUnitario === 0) {
                return;
            }

            this.handleActualizarOAgregarProducto(
                producto,
                presentacionSeleccionada,
                precioUnitario,
                cantidadIngresada
            );
        } else {
            const cantidadIngresada = parseInt(
                document.getElementById("cantidadInput").value
            );

            this.showCantidadModal(producto);

            this.disableAllOptions();
            this.enableOptionsBasedOnProduct(producto);

            this.autoSelectOption();

            const presentacionSeleccionada = this.selectPresentacion.value;
            const precioUnitario = this.getPrecioUnitario(
                producto,
                presentacionSeleccionada
            );

            this.displayPrecioUnitarioYTotal(precioUnitario, cantidadIngresada);
        }
    }

    showCantidadModal(producto) {
        this.cantidadModal.producto = producto;
        this.cantidadModal.show();
    }

    disableAllOptions() {
        for (const option of this.selectPresentacion.options) {
            option.disabled = true;
        }
    }

    enableOptionsBasedOnProduct(producto) {
        if (producto.disponible_caja) {
            this.enableOption("disponible_caja");
        }
        if (producto.disponible_blister) {
            this.enableOption("disponible_blister");
        }
        if (producto.disponible_unidad) {
            this.enableOption("disponible_unidad");
        }
    }

    enableOption(value) {
        this.selectPresentacion.querySelector(
            `[value="${value}"]`
        ).disabled = false;
    }

    autoSelectOption() {
        const opcionesDisponibles = Array.from(
            this.selectPresentacion.options
        ).filter((opcion) => !opcion.disabled);
        if (opcionesDisponibles.length === 1) {
            opcionesDisponibles[0].selected = true;
        }
    }

    getPrecioUnitario(producto, presentacionSeleccionada) {
        switch (presentacionSeleccionada) {
            case "disponible_caja":
                return producto.precio_caja;
            case "disponible_blister":
                return producto.precio_blister;
            case "disponible_unidad":
                return producto.precio_unidad;
            default:
                // Manejar el caso por defecto o mostrar un mensaje de error si es necesario
                return 0;
        }
    }

    displayPrecioUnitarioYTotal(precioUnitario, cantidadIngresada) {
        this.precioUnitarioInput.value = precioUnitario;
        const totalPrecioProducts = precioUnitario * cantidadIngresada;
        this.totalPrecioCompraInput.value = totalPrecioProducts;
    }

    handleCantidadPagadaInputChange() {
        const inputCantidadPagada = document.getElementById(
            "inputCantidadPagada"
        );
        const cantidadPagada = parseFloat(inputCantidadPagada.value);
        const granTotal =
            parseFloat(document.querySelector(".granTotal").textContent) || 0;

        // Calcula el cambio
        const cambio = cantidadPagada - granTotal;

        // Actualiza el valor del input de cambio
        const inputCambio = document.getElementById("inputCambio"); // Reemplaza 'inputCambio' con el ID real de tu input de cambio
        inputCambio.value = cambio.toFixed(0); // Puedes ajustar la cantidad de decimales según tus necesidades
    }

    actualizarTabla() {
        // Obtener la referencia del cuerpo de la tabla
        var tbody = document.querySelector("#tablaProductos tbody");

        // Limpiar el contenido actual del cuerpo de la tabla
        tbody.innerHTML = "";

        function formatearForma(valor) {
            switch (valor) {
                case "disponible_caja":
                    return "Caja";
                case "disponible_blister":
                    return "Blister";
                case "disponible_unidad":
                    return "Unidad";
                // Agrega más casos según sea necesario
                default:
                    return valor;
            }
        }

        let subGranTotal = 0;

        // Iterar sobre el array y agregar filas al cuerpo de la tabla
        productosParaVenta.forEach(function (producto, index) {
            var fila = tbody.insertRow();
            fila.dataset.index = index;

            for (var key in producto) {
                var celda = fila.insertCell();
                celda.textContent = producto[key];

                // Agregar una clase a la celda de la columna id_producto
                if (key === "id_producto") {
                    celda.classList.add("ocultar-columna-id");
                }

                if (
                    ["precio_unitario", "descuento", "subtotal"].includes(key)
                ) {
                    celda.style.textAlign = "right";

                    // celda.textContent = formatearMoneda(producto[key]);
                }

                if (key === "subtotal") {
                    subGranTotal += parseFloat(producto[key]);
                }

                if (key === "cantidad") {
                    celda.classList.add("text-center");
                }

                if (key === "forma") {
                    // Formatear el valor del campo 'forma'
                    celda.textContent = formatearForma(producto[key]);
                }
            }

            /* // Agregar una celda para el botón de eliminar
            var celdaEliminar = fila.insertCell();
            celdaEliminar.classList.add("celda-eliminar");

            var btnEliminar = document.createElement("button");
            btnEliminar.classList.add("btn-eliminar");
            btnEliminar.innerHTML = '<i class="fas fa-trash-alt"></i>';

            btnEliminar.addEventListener(
                "click",
                function () {
                    // Llama a EliminarFila sin necesidad de almacenar en una variable
                    this.EliminarFila(fila.dataset.index);
                }.bind(this)
            );

            celdaEliminar.appendChild(btnEliminar); */
        });

        this.calcularDatosGlobales();

    }

    calcularDatosGlobales()
    {
        let descuentoGlobal = 0;
        let subTotalGlobal = 0;
        let ivaTotalGlobal = 0;
        let granTotal = 0;

         // Recorrer el array productosParaVenta
        productosParaVenta.forEach(producto => {
            // Realizar cálculos según tus necesidades
            const subtotal = parseFloat(producto.subtotal);
            const itemDescuento = subtotal * (parseFloat(producto.descuento) / 100);
            const itemIva = subtotal * (parseFloat(producto.iva) / 100);

            // Acumular valores
            descuentoGlobal += itemDescuento;
            subTotalGlobal += subtotal;
            ivaTotalGlobal += itemIva;
        });

        // Calcular granTotal como la suma de descuentoGlobal, subTotalGlobal e ivaTotalGlobal
        granTotal = descuentoGlobal + subTotalGlobal + ivaTotalGlobal;



        // Actualizar elementos en el DOM si es necesario
         const granTotalFormateado = granTotal;
        document.querySelector('.granTotal').textContent = granTotalFormateado;
        document.querySelector('.descuentoGlobal').textContent = descuentoGlobal;
        document.querySelector('.subTotalGlobal').textContent = subTotalGlobal;
        document.querySelector('.ivaTotalGlobal').textContent = ivaTotalGlobal;

    }


    reinicializarVariables() {
        // Reinicializar las variables según sea necesario
        this.cantidadInput.value = 1; // Limpiar el campo de cantidad
        this.selectPresentacion.value = ""; // Reiniciar el select de presentación
        this.precioUnitarioInput.value = ""; // Limpiar el campo de precio unitario
        this.totalPrecioCompraInput.value = ""; // Limpiar el campo de total
        this.productoActual = "";
        // Otros campos y variables que necesiten reinicialización
    }
}

document.addEventListener("livewire:load", function () {
    // Escuchar un evento de Livewire
    Livewire.on(
        "agregarProductoAlArraySearch",
        function (producto, opcionSeleccionada) {
            // Manejar el evento en JavaScript
            console.log("Producto agregado:", producto);

            // Puedes realizar acciones adicionales aquí
        }
    );
});

// Crear una instancia de la clase para empezar el proceso
const productManager = new ProductManager();



/*---------------------Script que recibe los datos desde el modal de busqueda  ------------*/
