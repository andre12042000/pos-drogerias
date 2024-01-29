var inputBlisterPorCaja = document.getElementById("inputBlisterPorCaja");
var inputUnidadesPorCaja = document.getElementById("inputUnidadesPorCaja");
var inputCostoPorCaja = document.getElementById("inputCostoPorCaja");
var inputCostoPorCaja = document.getElementById("inputCostoPorCaja");

var inputStockActualEnCajas = document.getElementById(
    "inputStockActualEnCajas"
);

// Asigna el evento input para ejecutar la función cuando el valor cambie
inputBlisterPorCaja.addEventListener(
    "input",
    cambiarValidacionInputBlisterPorCaja
);

// Asigna el evento input para ejecutar la función cuando el valor cambie de unidades por caja
inputUnidadesPorCaja.addEventListener(
    "input",
    cambiarValidacionInputUnidadesPorCaja
);

// Asigna el evento input para ejecutar la función cuando el valor cambie de costo por caja
inputCostoPorCaja.addEventListener("input", realizarCalculosPreciosDeVentas);

inputStockActualEnCajas.addEventListener("input", permitirActualizar);

function permitirActualizar() {
    var inputPrecioVentaCaja = document.getElementById("inputPrecioVentaCaja");
    var precioVentaCajaValue = parseFloat(inputPrecioVentaCaja.value);

    var inputBlisterPorCaja = parseFloat(
        document.getElementById("inputBlisterPorCaja").value
    );
    var inputUnidadesPorCaja = parseFloat(
        document.getElementById("inputUnidadesPorCaja").value
    );
    var totalCajasParaCalcularItems = parseFloat(
        document.getElementById("inputStockActualEnCajas").value
    );

    if (
        !isNaN(inputBlisterPorCaja) &&
        !isNaN(totalCajasParaCalcularItems) &&
        inputBlisterPorCaja > 0 &&
        totalCajasParaCalcularItems > 0
    ) {
        var inputStockBlister = document.getElementById("inputStockBlister");
        inputStockBlister.value =
            totalCajasParaCalcularItems * inputBlisterPorCaja;
    }

    if (
        !isNaN(inputUnidadesPorCaja) &&
        !isNaN(totalCajasParaCalcularItems) &&
        inputUnidadesPorCaja > 0 &&
        totalCajasParaCalcularItems > 0
    ) {
        var inputStockUnidad = document.getElementById("inputStockUnidad");
        inputStockUnidad.value =
            totalCajasParaCalcularItems * inputUnidadesPorCaja;
    }

    if (isNaN(precioVentaCajaValue) || precioVentaCajaValue <= 0) {
        // Si el valor no es un número o es menor o igual a cero
        inputPrecioVentaCaja.classList.add("is-invalid");
    } else {
        // Si el valor es válido, quita la clase 'is-invalid'
        inputPrecioVentaCaja.classList.remove("is-invalid");

        // Habilita el botón de actualizar
        var btnActualizar = document.getElementById("btnActualizar");
        btnActualizar.disabled = false;
    }
}

function realizarCalculosPreciosDeVentas() {
    var inputBlisterPorCaja = parseFloat(
        document.getElementById("inputBlisterPorCaja").value
    );
    var inputUnidadesPorCaja = parseFloat(
        document.getElementById("inputUnidadesPorCaja").value
    );
    var inputCostoPorCaja = parseFloat(
        document.getElementById("inputCostoPorCaja").value
    );
    var inputCostoPorCaja = parseFloat(
        document.getElementById("inputCostoPorCaja").value
    );

    var inputPrecioVentaCaja = document.getElementById("inputPrecioVentaCaja");

    inputPrecioVentaCaja.value = calculardoraPreciosVenta(
        inputCostoPorCaja,
        "Caja"
    );

    if (!isNaN(inputBlisterPorCaja) && inputBlisterPorCaja > 0) {
        var inputCostoPorBlister = document.getElementById(
            "inputCostoPorBlister"
        );
        var inputPrecioVentaBlister = document.getElementById(
            "inputPrecioVentaBlister"
        );
        inputCostoPorBlister.value = CalculosPreciosVentaBlister();
        inputPrecioVentaBlister.value = calculardoraPreciosVenta(
            inputCostoPorBlister.value,
            "Blister"
        );
    }

    if (!isNaN(inputUnidadesPorCaja) && inputUnidadesPorCaja > 0) {
        var inputCostoPorUnidad = document.getElementById(
            "inputCostoPorUnidad"
        );
        var inputPrecioVentaUnidad = document.getElementById(
            "inputPrecioVentaUnidad"
        );

        inputCostoPorUnidad.value = calcularPrecosVentasUnidad();
        inputPrecioVentaUnidad.value = calculardoraPreciosVenta(
            inputCostoPorUnidad.value,
            "Unidad"
        );
    }
}

function calcularPrecosVentasUnidad() {
    var inputCostoPorCaja = parseFloat(
        document.getElementById("inputCostoPorCaja").value
    );
    var inputUnidadesPorCaja = parseFloat(
        document.getElementById("inputUnidadesPorCaja").value
    );

    var costoPorBlister = parseFloat(inputCostoPorCaja / inputUnidadesPorCaja);

    return costoPorBlister;
}

function CalculosPreciosVentaBlister() {
    var inputCostoPorCaja = parseFloat(
        document.getElementById("inputCostoPorCaja").value
    );
    var inputBlisterPorCaja = parseFloat(
        document.getElementById("inputBlisterPorCaja").value
    );

    var costoPorBlister = parseFloat(inputCostoPorCaja / inputBlisterPorCaja);

    return costoPorBlister;
}

function calculardoraPreciosVenta(monto_calcular, tipo) {
    // Obtenemos el valor del iva del input
    var ivaPorcentaje =
        parseFloat(document.getElementById("inputIva").value) / 100;
    var montoNumerico = parseFloat(monto_calcular);

    var selectedIndex = presentacionesSelect.selectedIndex;

    if (selectedIndex !== -1) {
        // Acceder a la opción seleccionada
        var selectedOption = presentacionesSelect.options[selectedIndex];

        // Acceder a los atributos
        var porcentajeGananciaCaja = parseFloat(selectedOption.por_caja) / 100;
        var porcentajeGananciaBlister =
            parseFloat(selectedOption.por_blister) / 100;
        var porcentajeGananciaUnidad =
            parseFloat(selectedOption.por_unidad) / 100;
    } else {
        var porcentajeGananciaCaja = parseFloat(15) / 100;
        var porcentajeGananciaBlister = parseFloat(20) / 100;
        var porcentajeGananciaUnidad = parseFloat(30) / 100;
    }

    var porcentajeGanancia;

    switch (tipo) {
        case "Caja":
            // Código a ejecutar si tipo es igual a "Caja"
            porcentajeGanancia = porcentajeGananciaCaja;
            break;

        case "Blister":
            // Código a ejecutar si tipo es igual a "Blister"
            porcentajeGanancia = porcentajeGananciaBlister;
            break;

        case "Unidad":
            // Código a ejecutar si tipo es igual a "Unidad"
            porcentajeGanancia = porcentajeGananciaUnidad;
            break;
    }

    var ivaCantidad = montoNumerico * ivaPorcentaje;
    var gananciaCantidad = montoNumerico * porcentajeGanancia;

    var precioVenta = montoNumerico + ivaCantidad + gananciaCantidad;

    return precioVenta;
}

/* function calcularPreciosVentas(IVA, ganancia, monto_calcular) {
    var ivaPorcentaje = parseFloat(IVA) / 100;
    var porcentajeGanancia = parseFloat(ganancia) / 100;

    // Asegúrate de que monto_calcular sea tratado como número
    var montoNumerico = parseFloat(monto_calcular);

    var ivaCantidad = montoNumerico * ivaPorcentaje;
    var gananciaCantidad = montoNumerico * porcentajeGanancia;

    var precioVenta = parseFloat(montoNumerico + ivaCantidad + gananciaCantidad);

    return precioVenta;
} */

// Definición de la función cambiarValidacionInputBlisterPorCaja
function cambiarValidacionInputBlisterPorCaja() {
    var inputBlisterPorCaja = document.getElementById("inputBlisterPorCaja");

    if (inputBlisterPorCaja.value > 0) {
        inputBlisterPorCaja.classList.remove("is-invalid");
    } else {
        inputBlisterPorCaja.classList.add("is-invalid");
    }
}

function cambiarValidacionInputUnidadesPorCaja() {
    var inputUnidadesPorCaja = document.getElementById("inputUnidadesPorCaja");

    if (inputUnidadesPorCaja.value > 0) {
        inputUnidadesPorCaja.classList.remove("is-invalid");
    } else {
        inputUnidadesPorCaja.classList.add("is-invalid");
    }
}

document
    .getElementById("presentacionesSelect")
    .addEventListener("change", function () {
        var selectedIndex = this.selectedIndex;

        if (selectedIndex !== -1) {
            var selectedOption = this.options[selectedIndex];

            // Acceder a los atributos adicionales
            var porCaja = selectedOption.por_caja;
            var porBlister = selectedOption.por_blister;
            var porUnidad = selectedOption.por_unidad;
        }
    });

function verificarSeleccionBlisterSelect() {
    var disponibleBlisterSelect = document.getElementById(
        "disponibleBlisterSelect"
    );
    var inputBlisterPorCaja = document.getElementById("inputBlisterPorCaja");
    var inputStockBlister = document.getElementById("inputStockBlister");
    var inputCostoPorBlister = document.getElementById("inputCostoPorBlister");
    var inputPrecioVentaBlister = document.getElementById(
        "inputPrecioVentaBlister"
    );

    if (disponibleBlisterSelect.value !== "1") {
        // Si el valor del select no es 1, desactivar los inputs
        inputBlisterPorCaja.disabled = true;
        inputStockBlister.disabled = true;
        inputCostoPorBlister.disabled = true;
        inputPrecioVentaBlister.disabled = true;
    } else {
        // Si el valor del select es 1, habilitar los inputs
        inputBlisterPorCaja.disabled = false;
        inputStockBlister.disabled = false;
        inputCostoPorBlister.disabled = false;
        inputPrecioVentaBlister.disabled = false;
        inputBlisterPorCaja.classList.add("is-invalid");
    }

    this.desbloquearInputsIniciales();
}

function verificarSeleccionUnidadSelect() {
    var disponibleUnidadSelect = document.getElementById(
        "disponibleUnidadSelect"
    );
    var inputUnidadesPorCaja = document.getElementById("inputUnidadesPorCaja");
    var inputStockUnidad = document.getElementById("inputStockUnidad");
    var inputCostoPorUnidad = document.getElementById("inputCostoPorUnidad");
    var input4Element = document.getElementById("inputPrecioVentaUnidad");

    if (disponibleUnidadSelect.value !== "1") {
        // Si el valor del select no es 1, desactivar los inputs
        inputUnidadesPorCaja.disabled = true;
        inputStockUnidad.disabled = true;
        inputCostoPorUnidad.disabled = true;
        input4Element.disabled = true;
    } else {
        // Si el valor del select es 1, habilitar los inputs
        inputUnidadesPorCaja.disabled = false;
        inputStockUnidad.disabled = false;
        inputCostoPorUnidad.disabled = false;
        input4Element.disabled = false;
        inputUnidadesPorCaja.classList.add("is-invalid");
    }

    this.desbloquearInputsIniciales();
}

function desbloquearInputsIniciales() {
    var disponibleUnidadSelect = document.getElementById(
        "disponibleUnidadSelect"
    ).value;
    var disponibleBlisterSelect = document.getElementById(
        "disponibleBlisterSelect"
    ).value;

    var inputCostoPorCaja = document.getElementById("inputCostoPorCaja");
    var inputPrecioVentaCaja = document.getElementById("inputPrecioVentaCaja");
    var inputStockActualEnCajas = document.getElementById(
        "inputStockActualEnCajas"
    );

    if (
        disponibleUnidadSelect != "Seleccionar" &&
        disponibleBlisterSelect != "Seleccionar"
    ) {
        inputCostoPorCaja.disabled = false;
        inputPrecioVentaCaja.disabled = false;
        inputStockActualEnCajas.disabled = false;
    } else {
        inputCostoPorCaja.disabled = true;
        inputPrecioVentaCaja.disabled = true;
        inputStockActualEnCajas.disabled = true;
    }
}

function getElementValue(elementId) {
    return document.getElementById(elementId).value;
}

function isValidPositiveNumber(value) {
    return value !== "" && !isNaN(value) && parseInt(value) > 0;
}

function validateAndToggleClass(elementId, isValid) {
    var element = document.getElementById(elementId);
    if (isValid) {
        element.classList.remove("is-invalid");
    } else {
        element.classList.add("is-invalid");
    }
}

function validarSelects() {
    var categorySelect = document.getElementById("categoriaSelect");
    var subcategoriaSelect = document.getElementById("subcategoriaSelect");
    var ubicacionesSelect = document.getElementById("ubicacionesSelect");
    var presentacionesSelect = document.getElementById("presentacionesSelect");
    var laboratoriosSelect = document.getElementById("laboratoriosSelect");

    // Verificar y agregar la clase 'is-invalid' a los select vacíos
    if (categorySelect.value === "") {
        categorySelect.classList.add("is-invalid");
    } else {
        categorySelect.classList.remove("is-invalid");
    }

    if (subcategoriaSelect.value === "") {
        subcategoriaSelect.classList.add("is-invalid");
    } else {
        subcategoriaSelect.classList.remove("is-invalid");
    }

    if (ubicacionesSelect.value === "") {
        ubicacionesSelect.classList.add("is-invalid");
    } else {
        ubicacionesSelect.classList.remove("is-invalid");
    }

    if (presentacionesSelect.value === "") {
        presentacionesSelect.classList.add("is-invalid");
    } else {
        presentacionesSelect.classList.remove("is-invalid");
    }

    if (laboratoriosSelect.value === "") {
        laboratoriosSelect.classList.add("is-invalid");
    } else {
        laboratoriosSelect.classList.remove("is-invalid");
    }

    // Verificar si algún select está vacío
    var algunSelectVacio =
        categorySelect.value === "" ||
        subcategoriaSelect.value === "" ||
        ubicacionesSelect.value === "" ||
        presentacionesSelect.value === "" ||
        laboratoriosSelect.value === "";

    // Realizar acciones adicionales si es necesario
    if (algunSelectVacio) {
        return;
    }
}

function actualizarInventario() {
    validarSelects();
    //variables para ajustar el producto
    var id = document.getElementById("inputId").value;
    var code = document.getElementById("inputCodigo").value;
    var name = document.getElementById("inputName").value;
    var category_id = document.getElementById("categoriaSelect").value;
    var subcategoria_id = document.getElementById("subcategoriaSelect").value;
    var ubicacion_id = document.getElementById("ubicacionesSelect").value;
    var presentacion_id = document.getElementById("presentacionesSelect").value;
    var laboratorio_id = document.getElementById("laboratoriosSelect").value;
    var lote = document.getElementById('lote_ajuste_inventario').value;
    var fecha_vencimiento = document.getElementById('fecha_vencimiento_ajuste_inventario').value;


    var iva_product = parseInt(document.getElementById("inputIva").value, 10);
    var stock_min = parseInt(
        document.getElementById("inputStockMinimo").value,
        10
    );

    var stock_max = parseInt(
        document.getElementById("inputStockMaximo").value,
        10
    );
    var disponible_caja = document.getElementById("disponibleCajaSelect").value;
    var disponible_blister = document.getElementById(
        "disponibleBlisterSelect"
    ).value;
    var disponible_unidad = document.getElementById(
        "disponibleUnidadSelect"
    ).value;
    var contenido_interno_caja = parseInt(
        document.getElementById("puntoReferenciaCaja").value,
        10
    );
    var contenido_interno_blister = parseInt(
        document.getElementById("inputBlisterPorCaja").value,
        10
    );
    var contenido_interno_unidad = parseInt(
        document.getElementById("inputUnidadesPorCaja").value,
        10
    );
    var costo_caja = parseInt(
        document.getElementById("inputCostoPorCaja").value,
        10
    );
    var costo_blister = parseInt(
        document.getElementById("inputCostoPorBlister").value,
        10
    );
    var costo_unidad = parseInt(
        document.getElementById("inputCostoPorUnidad").value,
        10
    );
    var precio_caja = parseInt(
        document.getElementById("inputPrecioVentaCaja").value,
        10
    );
    var precio_blister = parseInt(
        document.getElementById("inputPrecioVentaBlister").value,
        10
    );
    var precio_unidad = parseInt(
        document.getElementById("inputPrecioVentaUnidad").value,
        10
    );
    var valor_iva_caja = calcularIVA(iva_product, costo_caja);
    var valor_iva_blister = calcularIVA(iva_product, costo_blister);
    var valor_iva_unidad = calcularIVA(iva_product, costo_unidad);

    var cantidad_caja = parseInt(
        document.getElementById("inputStockActualEnCajas").value,
        10
    );
    var cantidad_blister = parseInt(
        document.getElementById("inputStockBlister").value,
        10
    );
    var cantidad_unidad = parseInt(
        document.getElementById("inputStockUnidad").value,
        10
    );

    /*-------------- Validaciones -------------------------*/

    var inputStockMinimo = document.getElementById("inputStockMinimo");
    if (inputStockMinimo.value <= 0) {
        inputStockMinimo.classList.add("is-invalid");
        return;
    }

    var inputStockMaximo = document.getElementById("inputStockMaximo");

    if (inputStockMaximo.value <= 0) {
        var inputStockMaximo = document.getElementById("inputStockMaximo");
        inputStockMaximo.classList.add("is-invalid");
        return;
    }

    var inputPrecioVentaCaja = document.getElementById("inputPrecioVentaCaja");

    if (inputPrecioVentaCaja.value <= 0) {
        inputPrecioVentaCaja.classList.add("is-invalid");
        return;
    }

    /*------------------ Fin validaciones ------------------------*/


    let datosInventario = {
        product_id: id,
        cantidad_caja: cantidad_caja,
        cantidad_blister: cantidad_blister,
        cantidad_unidad: cantidad_unidad,
        lote: lote,
        fecha_vencimiento: fecha_vencimiento,
    };

    let datosProducto = {
        id: id,
        code: code,
        name: name,
        category_id: category_id,
        subcategoria_id: subcategoria_id,
        ubicacion_id: subcategoria_id,
        ubicacion_id: ubicacion_id,
        presentacion_id: presentacion_id,
        laboratorio_id: laboratorio_id,
        iva_product: iva_product,
        stock_min: stock_min,
        stock_max: stock_max,
        disponible_caja: disponible_caja,
        disponible_blister: disponible_blister,
        disponible_unidad: disponible_unidad,
        contenido_interno_caja: contenido_interno_caja,
        contenido_interno_blister: contenido_interno_blister,
        contenido_interno_unidad: contenido_interno_unidad,
        costo_caja: costo_caja,
        costo_blister: costo_blister,
        costo_unidad: costo_unidad,
        precio_caja: precio_caja,
        precio_blister: precio_blister,
        precio_unidad: precio_unidad,
        valor_iva_caja: valor_iva_caja,
        valor_iva_blister: valor_iva_blister,
        valor_iva_unidad: valor_iva_unidad,
    };

    Livewire.emit("ajusteInventarioEvent", {
        dataProduct: datosProducto,
        datosInventario: datosInventario,
    });

    // Variables para ajustar el inventario
}

function validarInputInventario() {}

function calcularIVA(porcentajeIVA, monto_calcular) {
    if (
        isNaN(monto_calcular) ||
        monto_calcular === "" ||
        isNaN(porcentajeIVA) ||
        porcentajeIVA === ""
    ) {
        return 0;
    }

    var iva = (parseFloat(monto_calcular) * parseFloat(porcentajeIVA)) / 100;

    return iva;
}
