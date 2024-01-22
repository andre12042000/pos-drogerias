document
    .getElementById("costo_caja")
    .addEventListener("change", verificarValores);
document
    .getElementById("costo_blister")
    .addEventListener("change", verificarValores);
document
    .getElementById("costo_unidad")
    .addEventListener("change", verificarValores);

    var presentacionData;

document
    .getElementById("presentacion_id")
    .addEventListener("change", function () {
        var selectedOption = this.options[this.selectedIndex];
        presentacionData = JSON.parse(
            selectedOption.getAttribute("data-presentacion")
        );

        // Ahora 'presentacionData' contiene el objeto con todos los datos de presentación
        var cajaToDisable = [
            "contenido_interno_caja",
            "costo_caja",
            "precio_caja",
        ];
        var blisterToDisable = [
            "contenido_interno_blister",
            "costo_blister",
            "precio_blister",
        ];
        var unidadToDisable = [
            "contenido_interno_unidad",
            "costo_unidad",
            "precio_unidad",
        ];

        cajaToDisable.forEach(function (id) {
            var inputToDisable = document.getElementById(id);
            if (presentacionData.disponible_caja === 1) {
                inputToDisable.removeAttribute("disabled");
            } else {
                inputToDisable.setAttribute("disabled", "disabled");
            }
            inputToDisable.value = "0";
        });

        blisterToDisable.forEach(function (id) {
            var inputToDisable = document.getElementById(id);
            if (presentacionData.disponible_blister === 1) {
                inputToDisable.removeAttribute("disabled");
            } else {
                inputToDisable.setAttribute("disabled", "disabled");
            }
            inputToDisable.value = "0";
        });

        unidadToDisable.forEach(function (id) {
            var inputToDisable = document.getElementById(id);
            if (presentacionData.disponible_unidad === 1) {
                inputToDisable.removeAttribute("disabled");
            } else {
                inputToDisable.setAttribute("disabled", "disabled");
            }
            inputToDisable.value = "0";
        });
    });

function verificarValores() {
    var ids = [
        "contenido_interno_caja",
        "contenido_interno_blister",
        "contenido_interno_unidad",
    ];

    // Objeto para almacenar los valores asociados a cada ID
    var valores = {};

    // Obtener los elementos y sus valores
    ids.forEach(function (id) {
        var elemento = document.getElementById(id);
        valores[id] = elemento.value;
    });

    var contenidoInternoCaja = valores.contenido_interno_caja;
    var contenidoInternoBlister = valores.contenido_interno_blister;
    var contenidoInternoUnidad = valores.contenido_interno_unidad;


    determinarCualFuncionUsar(contenidoInternoCaja, contenidoInternoBlister, contenidoInternoUnidad);

}

function determinarCualFuncionUsar(contenidoInternoCaja, contenidoInternoBlister, contenidoInternoUnidad){





    console.log(presentacionData);

     console.log(contenidoInternoCaja, contenidoInternoBlister, contenidoInternoUnidad);
    if (contenidoInternoCaja == 0 && contenidoInternoBlister == 0 && contenidoInternoUnidad == 0) {
        // Emitir una alerta indicando que todas las variables son 0
        return 0;
    } else if (
        contenidoInternoCaja > 0 &&
        contenidoInternoBlister > 0 &&
        contenidoInternoUnidad > 0
    ) {
    } else if (
        contenidoInternoCaja > 0 &&
        contenidoInternoBlister > 0 &&
        contenidoInternoUnidad == 0
    ) {
    } else if (
        contenidoInternoCaja > 0 &&
        contenidoInternoBlister == 0 &&
        contenidoInternoUnidad == 0
    ) {
    } else if (
        contenidoInternoCaja == 0 &&
        contenidoInternoBlister > 0 &&
        contenidoInternoUnidad > 0
    ) {
    } else if (
        contenidoInternoCaja == 0 &&
        contenidoInternoBlister > 0 &&
        contenidoInternoUnidad == 0
    ) {
    } else if (
        contenidoInternoCaja == 0 &&
        contenidoInternoBlister == 0 &&
        contenidoInternoUnidad > 0
    ) {
       return calcularPrecioPorUnidad(contenidoInternoUnidad);
    }

}




/*---------------------- Opciones para el calculo de los precios ----------------------------*/

function calcularPrecioPorUnidad(contenidoInternoUnidad) {
    var cantidad_total_cajas = document.getElementById("cantidad_total_cajas");
    var cantidad_total_blister = document.getElementById("cantidad_total_blister");
    var cantidad_total_unidad = document.getElementById("cantidad_total_unidad");
    var contenido_interno_unidad = document.getElementById("contenido_interno_unidad")
    var precio_unidad = document.getElementById("precio_unidad")
    var costo_unidad = document.getElementById("costo_unidad")
    var porc_iva = document.getElementById("iva_product")


    cantidad_total_cajas.value = '0';
    cantidad_total_blister.value = '0';
    cantidad_total_unidad.value = contenidoInternoUnidad; //Cantidad real de articulos que ingresan

    /*--------------Calculos -------------------*/

    porc_iva =  convertirPorcentajeADecimal(porc_iva.value);
    var ganancia = calcularGanancia(costo_unidad.value, presentacionData.por_unidad)

    console.log(ganancia);









}

function calcularGanancia(costo, porcentajeGanancia )
{
    var ganancia = costo * (porcentajeGanancia / 100);

    return ganancia;

}

function convertirPorcentajeADecimal(ivaPorcentaje) {
    // Manejar caso especial cuando el IVA es 0
    if (ivaPorcentaje === 0) {
        return 0;
    }

    return ivaPorcentaje / 100;
}

// Función para la opción 2
function calcularPrecioOpcion2() {
    // Realizar cálculos específicos para la opción 2
}

// Función para la opción 3
function calcularPrecioOpcion3() {
    // Realizar cálculos específicos para la opción 3
}

// Función para la opción 4
function calcularPrecioOpcion4() {
    // Realizar cálculos específicos para la opción 4
}

// Función para la opción 5
function calcularPrecioOpcion5() {
    // Realizar cálculos específicos para la opción 5
}
