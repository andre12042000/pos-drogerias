
var productosParaVenta = [];
var productoActual;

class ProductManager {
    constructor() {
        this.productosParaVenta = [];
        this.productoActual;
        this.cantidadModal = new bootstrap.Modal(document.getElementById('cantidadModal'));
        this.selectPresentacion = document.getElementById('selectPresentacion');
        this.totalPrecioCompraInput = document.getElementById('totalPrecioCompraInput');
        this.precioUnitarioInput = document.getElementById('precioUnitarioInput');
        this.cantidadInput = document.getElementById('cantidadInput');

        const cantidadInput = document.getElementById('cantidadInput');
        cantidadInput.addEventListener('input', () => this.handleCantidadInputChange());

        const precioUnitarioInput = document.getElementById('precioUnitarioInput');
        precioUnitarioInput.addEventListener('input', () => this.handlePrecioUnitarioInputChange());

        const selectPresentacion = document.getElementById('selectPresentacion');
        selectPresentacion.addEventListener('change', () => this.handlePresentacionChange());

        const agregarBtn = document.getElementById('agregarProductosArrayVenta');
        agregarBtn.addEventListener('click', () => this.handleAgregarButtonClick());

        this.initializeListeners();
    }

    initializeListeners() {
        window.addEventListener('agregarProductoAlArrayCode', (event) => this.handleAgregarProducto(event));
    }

    handleAgregarButtonClick() {

     // Obtener la información del producto del modal (puedes acceder a esto según tu implementación específica)
        const producto = this.cantidadModal.producto;
        const cantidadIngresada = parseInt(this.cantidadInput.value);

        // Validar que la cantidad sea mayor que 0
        if (cantidadIngresada <= 0 || isNaN(cantidadIngresada)) {
            console.log('Ingrese una cantidad válida mayor que 0.');
            return;
        }

        // Validar que se haya seleccionado una presentación
        const presentacionSeleccionada = this.selectPresentacion.value;
        if (!presentacionSeleccionada) {
            console.log('Seleccione una presentación.');
            return;
        }

        // Validar que el precio unitario sea mayor que 0
        const precioUnitario = parseFloat(this.precioUnitarioInput.value);
        if (precioUnitario <= 0 || isNaN(precioUnitario)) {
            console.log('Ingrese un precio unitario válido mayor que 0.');
            return;
        }

        // Validar que el subtotal sea mayor que 0
        const subtotal = parseFloat(this.totalPrecioCompraInput.value);
        if (subtotal <= 0 || isNaN(subtotal)) {
            console.log('Ingrese un subtotal válido mayor que 0.');
            return;
        }



        // Crear un nuevo producto
         const nuevoProducto = {
            item: 3,  // Puedes gestionar este valor según tus necesidades
            id_producto: producto.id,
            codigo: producto.code,
            descripcion: producto.name,
            forma: presentacionSeleccionada,
            precio_unitario: precioUnitario,
            cantidad: cantidadIngresada,
            descuento: 0,  // Puedes gestionar este valor según tus necesidades
            subtotal: subtotal,
        };



        // Agregar el nuevo producto al array productosParaVenta
        productosParaVenta.push(nuevoProducto);
        this.reinicializarVariables();
        this.actualizarTabla();

        this.cantidadModal.hide();

    }


    handlePrecioUnitarioInputChange() {
        const nuevoPrecioUnitario = parseFloat(document.getElementById('precioUnitarioInput').value);
        const cantidadIngresada = parseInt(document.getElementById('cantidadInput').value, 10);

        // Verifica si la cantidadIngresada es un número válido antes de continuar
        if (isNaN(cantidadIngresada)) {
            console.log('Ingrese una cantidad válida antes de cambiar el precio unitario.');
            return;
        }

        this.displayPrecioUnitarioYTotal(nuevoPrecioUnitario, cantidadIngresada);
    }

    handleCantidadInputChange() {
        console.log('cambie cantidad');

         // Obtener el valor de cantidadInput
         const nuevoValor = parseInt(document.getElementById('cantidadInput').value);

         // Verificar si se ha seleccionado una presentación
         const presentacionSeleccionada = this.selectPresentacion.value;

         if (!presentacionSeleccionada) {
             // Si no se ha seleccionado una presentación, mostrar un mensaje o realizar la lógica correspondiente
             console.log('Seleccione una presentación antes de ingresar la cantidad.');
             return;
         }

         // Verificar si hay un valor en el precio unitario
         const precioUnitarioInputValue = this.precioUnitarioInput.value;

         if (!precioUnitarioInputValue) {
             // Si no hay un valor en el precio unitario, mostrar un mensaje o realizar la lógica correspondiente
             console.log('Ingrese un precio unitario antes de ingresar la cantidad.');
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
            console.log('Seleccione un producto antes de cambiar la presentación.');
            return;
        }

        // Obtener el precio unitario según la nueva presentación seleccionada
        const precioUnitario = this.getPrecioUnitario(producto, nuevoValor);

        // Obtener la cantidad actualmente ingresada
        const cantidadIngresada = parseInt(document.getElementById('cantidadInput').value);

        // Calcular y mostrar el precio unitario y el total
        this.displayPrecioUnitarioYTotal(precioUnitario, cantidadIngresada);
    }

    handleAgregarProducto(event) {
        productoActual = event.detail.producto;
        const producto = event.detail.producto;
        const cantidadIngresada = parseInt(document.getElementById('cantidadInput').value);

        this.showCantidadModal(producto);

        this.disableAllOptions();
        this.enableOptionsBasedOnProduct(producto);

        this.autoSelectOption();

        const presentacionSeleccionada = this.selectPresentacion.value;
        const precioUnitario = this.getPrecioUnitario(producto, presentacionSeleccionada);

        this.displayPrecioUnitarioYTotal(precioUnitario, cantidadIngresada);

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
            this.enableOption('disponible_caja');
        }
        if (producto.disponible_blister) {
            this.enableOption('disponible_blister');
        }
        if (producto.disponible_unidad) {
            this.enableOption('disponible_unidad');
        }
    }

    enableOption(value) {
        this.selectPresentacion.querySelector(`[value="${value}"]`).disabled = false;
    }

    autoSelectOption() {
        const opcionesDisponibles = Array.from(this.selectPresentacion.options).filter((opcion) => !opcion.disabled);
        if (opcionesDisponibles.length === 1) {
            opcionesDisponibles[0].selected = true;
        }
    }

    getPrecioUnitario(producto, presentacionSeleccionada) {
        switch (presentacionSeleccionada) {
            case 'disponible_caja':
                return producto.precio_caja;
            case 'disponible_blister':
                return producto.precio_blister;
            case 'disponible_unidad':
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

    actualizarTabla() {
        // Obtener la referencia del cuerpo de la tabla
        var tbody = document.querySelector('#tablaProductos tbody');

        // Limpiar el contenido actual del cuerpo de la tabla
        tbody.innerHTML = '';

        // Iterar sobre el array y agregar filas al cuerpo de la tabla
        productosParaVenta.forEach(function(producto) {
            var fila = tbody.insertRow();
            for (var key in producto) {
                var celda = fila.insertCell();
                celda.textContent = producto[key];

                // Agregar una clase a la celda de la columna id_producto
                if (key === 'id_producto') {
                    celda.classList.add('ocultar-columna-id');
                }
            }
        });
    }


    reinicializarVariables() {
        // Reinicializar las variables según sea necesario
        this.cantidadInput.value = 1;  // Limpiar el campo de cantidad
        this.selectPresentacion.value = '';  // Reiniciar el select de presentación
        this.precioUnitarioInput.value = '';  // Limpiar el campo de precio unitario
        this.totalPrecioCompraInput.value = '';  // Limpiar el campo de total
        this. productoActual = '';
        // Otros campos y variables que necesiten reinicialización
    }


}

document.addEventListener('livewire:load', function () {
    // Escuchar un evento de Livewire
    Livewire.on('agregarProductoAlArraySearch', function (producto, opcionSeleccionada) {
        // Manejar el evento en JavaScript
        console.log('Producto agregado:', producto);

        // Puedes realizar acciones adicionales aquí
    });
});


// Crear una instancia de la clase para empezar el proceso
const productManager = new ProductManager();


/*---------------------Script que recibe los datos desde el modal de busqueda  ------------*/







