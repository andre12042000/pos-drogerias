var presentacionData;
var valor_iva_caja = 0;
var valor_iva_blister = 0;
var valor_iva_unidad = 0;



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
   // var nuevoPrecioCaja = costo_caja + ganancia + iva;
    valor_iva_caja = iva;

   // Livewire.emit('actualizarPrecioCaja', nuevoPrecioCaja);

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



    costo_blister.value = parseFloat(costo_caja.value / cantidad_Blister.value);
    costoBlisterValue = parseFloat(costo_blister.value);


    var ganancia = parseFloat(costoBlisterValue * (gananciaPorcentaje / 100));


    var iva = parseFloat(costoBlisterValue * ivaPorcentaje);
    precio_blister.value = costoBlisterValue + ganancia + iva;

    valor_iva_blister = iva;

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
    valor_iva_unidad = iva;

}

/*---------------------------Convertir porcentaje iva ---------------------*/

function convertirPorcentajeADecimal(ivaPorcentaje) {
    // Manejar caso especial cuando el IVA es 0
    if (ivaPorcentaje === 0) {
        return 0;
    }

    return ivaPorcentaje / 100;
}

/*----------------Capturar datos procesados para enviar al backend -------*/

function capturarYEnviarDatos() {
    var datos = {
        costo_caja: document.getElementById("costo_caja").value,
        iva_product: document.getElementById("iva_product").value,
        precio_caja: document.getElementById("precio_caja").value,
        precio_blister: document.getElementById("precio_blister").value,
        costo_blister: document.getElementById("costo_blister").value,
        costo_unidad: document.getElementById("costo_unidad").value,
        precio_unidad: document.getElementById("precio_unidad").value,
        valor_iva_caja: valor_iva_caja,
        valor_iva_blister: valor_iva_blister,
        valor_iva_unidad: valor_iva_unidad,
        // Agrega los demás campos aquí
    };

    Livewire.emit('guardarDatosEvent', datos);
}