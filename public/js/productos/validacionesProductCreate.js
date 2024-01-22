var presentacionData;

/*----------------------Metodos para habilitar o deshabilitar inputs ----------*/
document
    .getElementById("disponible_blister")
    .addEventListener("change", habilitarItemsBlister);

document
    .getElementById("disponible_unidad")
    .addEventListener("change", habilitarItemsUnidad);

document
    .getElementById("presentacion_id")
    .addEventListener("change", function () {
        // Obtener el elemento select
        var presentacion_id = document.getElementById("presentacion_id");

        // Obtener el valor seleccionado
        var valorSeleccionado = presentacion_id.value;

        var selectedOption = this.options[this.selectedIndex];
        presentacionData = JSON.parse(
            selectedOption.getAttribute("data-presentacion")
        );

        // Ahora 'presentacionData' contiene el objeto con todos los datos de presentación
        var cajaToDisable = [
            "costo_caja",
            "precio_caja",
            "disponible_blister",
            "disponible_unidad",
        ];

        cajaToDisable.forEach(function (id) {
            var inputToDisable = document.getElementById(id);
            if (valorSeleccionado != "Seleccionar") {
                inputToDisable.removeAttribute("disabled");
            } else {
                inputToDisable.setAttribute("disabled", "disabled");
            }
            inputToDisable.value = "0";
        });
    });

function habilitarItemsBlister() {
    // Obtener el elemento select
    var disponible_blister = document.getElementById("disponible_blister");

    // Obtener el valor seleccionado
    var valorSeleccionado = disponible_blister.value;

    var blisterToDisable = [
        "contenido_interno_blister",
        "costo_blister",
        "precio_blister",
    ];

    blisterToDisable.forEach(function (id) {
        var inputToDisable = document.getElementById(id);
        if (valorSeleccionado === "1") {
            inputToDisable.removeAttribute("disabled");
        } else {
            inputToDisable.setAttribute("disabled", "disabled");
        }
        inputToDisable.value = "0";
    });
}

function habilitarItemsUnidad() {
    var disponible_unidad = document.getElementById("disponible_unidad");

    // Obtener el valor seleccionado
    var valorSeleccionado = disponible_unidad.value;

    var blisterToDisable = [
        "contenido_interno_unidad",
        "costo_unidad",
        "precio_unidad",
    ];

    blisterToDisable.forEach(function (id) {
        var inputToDisable = document.getElementById(id);
        if (valorSeleccionado === "1") {
            inputToDisable.removeAttribute("disabled");
        } else {
            inputToDisable.setAttribute("disabled", "disabled");
        }
        inputToDisable.value = "0";
    });
}

/*-------------------------Fin metodos para habilitar inputs -------------*/

/*-------------Calculos matematicos ---------------------------*/

document
    .getElementById("costo_caja")
    .addEventListener("change", calcularPrecioVentaCaja);

function calcularPrecioVentaCaja() {
    var costo_caja = document.getElementById("costo_caja");
    var iva_product = document.getElementById("iva_product");
    var precio_caja = document.getElementById("precio_caja");
    var cantidad_Blister = document.getElementById("contenido_interno_blister");
    var cantidad_Unidad = document.getElementById("contenido_interno_unidad");

    ivaPorcentaje = convertirPorcentajeADecimal(iva_product.value);
    gananciaPorcentaje = presentacionData.por_caja;
    costo_caja = parseFloat(costo_caja.value);

    var ganancia = parseFloat(costo_caja * (gananciaPorcentaje / 100));
    var iva = parseFloat(costo_caja * ivaPorcentaje);
    precio_caja.value = costo_caja + ganancia + iva;

    if(cantidad_Blister.value > 0){
        calcularPreciosBlister();
    }

    if(cantidad_Unidad.value > 0){
        calcularPreciosUnidad();
    }
}

function calcularPreciosBlister(){
    var costo_caja = document.getElementById("costo_caja");
    var iva_product = document.getElementById("iva_product");
    var cantidad_Blister = document.getElementById("contenido_interno_blister");

    ivaPorcentaje = convertirPorcentajeADecimal(iva_product.value);
    gananciaPorcentaje = presentacionData.por_blister;

    console.log(gananciaPorcentaje);

    costo_blister.value = parseFloat(costo_caja.value / cantidad_Blister.value);
    costoBlisterValue = parseFloat(costo_blister.value);


    var ganancia = parseFloat(costoBlisterValue * (gananciaPorcentaje / 100));


    var iva = parseFloat(costoBlisterValue * ivaPorcentaje);
    precio_blister.value = costoBlisterValue + ganancia + iva;

}

function calcularPreciosUnidad(){
    var costo_caja = document.getElementById("costo_caja");
    var iva_product = document.getElementById("iva_product");
    var cantidad_Unidad = document.getElementById("contenido_interno_unidad");

    ivaPorcentaje = convertirPorcentajeADecimal(iva_product.value);
    gananciaPorcentaje = presentacionData.por_unidad;



    costo_unidad.value = parseFloat(costo_caja.value / cantidad_Unidad.value);
    costoUnidadValue = parseFloat(costo_unidad.value);


    var ganancia = parseFloat(costoUnidadValue * (gananciaPorcentaje / 100));


    var iva = parseFloat(costoUnidadValue * ivaPorcentaje);
    precio_unidad.value = costoUnidadValue + ganancia + iva;

}

/*---------------------------Convertir porcentaje iva ---------------------*/

function convertirPorcentajeADecimal(ivaPorcentaje) {
    // Manejar caso especial cuando el IVA es 0
    if (ivaPorcentaje === 0) {
        return 0;
    }

    return ivaPorcentaje / 100;
}
