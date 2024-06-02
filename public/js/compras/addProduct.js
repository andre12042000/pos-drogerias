window.addEventListener('calcularDatosEvent', event => {

    var cantidad = document.getElementById('cantidad');
    var precio_compra = document.getElementById('precio_compra');
    var iva = document.getElementById('iva');
   // var descuento = document.getElementById('descuento');
    var precio_venta_caja = document.getElementById('precio_venta_caja');
    var name = document.getElementById('name_add');
    var fecha_vencimiento = document.getElementById('fecha_vencimiento');
    var precio_venta_blister = document.getElementById('precio_venta_blister');
    var precio_venta_unidad = document.getElementById('precio_venta_unidad');
    var lote = document.getElementById('lote');

    fecha_vencimiento.removeAttribute('disabled');
    lote.removeAttribute('disabled');
    name.removeAttribute('disabled');
    cantidad.removeAttribute('disabled');
    precio_compra.removeAttribute('disabled');
    iva.removeAttribute('disabled');
  //  descuento.removeAttribute('disabled');
    precio_venta_caja.removeAttribute('disabled');



    cantidad.classList.add('is-invalid');
    precio_compra.classList.add('is-invalid');
    iva.classList.add('is-invalid');
  //  descuento.classList.add('is-invalid');




    var disponible_blister = document.getElementById('input_disponible_blister');
    var disponible_unidad = document.getElementById('input_disponible_unidad');


    if(parseFloat(disponible_blister.value) > 0){
        precio_venta_blister.removeAttribute('disabled');
    }else{
        precio_venta_blister.classList.add('disabled');
    }

    if(parseFloat(disponible_unidad.value) > 0)
    {
        precio_venta_unidad.removeAttribute('disabled');
    }else{
        precio_venta_unidad.classList.add('disabled');
    }



})

document.addEventListener("DOMContentLoaded", function() {

    var cantidad = document.getElementById('cantidad');
    var precio_compra = document.getElementById('precio_compra');
    var iva = document.getElementById('iva');
    var descuento = document.getElementById('descuento');
    var obsequio = document.getElementById('obsequio');

    var submitDataBack = document.getElementById('agregarProductoBtn');

     // Agregar listeners para los eventos de cambio
     cantidad.addEventListener('change', activarBotonAgregarProducto);
     precio_compra.addEventListener('change', obtenerPreciosDeVenta);
     iva.addEventListener('change', obtenerPreciosDeVenta);
     descuento.addEventListener('change', obtenerPreciosDeVenta);


     function activarBotonAgregarProducto()
     {
        if(cantidad.value > 0){
            var botonAgregarProducto = document.getElementById('agregarProductoBtn');
            botonAgregarProducto.removeAttribute('disabled');
        }

     }

     function obtenerPreciosDeVenta() {

       // calcularPreciosVenta();
    }

    submitDataBack.addEventListener('click', function() {


        var name = document.getElementById('name_add').value;
        var lote = document.getElementById('lote').value;
        var fecha_vencimiento = document.getElementById('fecha_vencimiento').value;

        var cantidad = document.getElementById('cantidad').value;
        var precio_compra = document.getElementById('precio_compra').value;
        var iva = document.getElementById('iva').value;
        var descuento = document.getElementById('descuento').value;

        var precio_venta_caja = document.getElementById('precio_venta_caja').value;
        var precio_venta_blister = document.getElementById('precio_venta_blister').value;
        var precio_venta_unidad = document.getElementById('precio_venta_unidad').value;

        var checkbox = document.getElementById("obsequio");
        var isChecked = checkbox.checked;
        var ingresaComoObsequio = false;

        // Realizar acciones en consecuencia
        if (isChecked) {
           ingresaComoObsequio = true;
        }


        var dataIngresoProducto = {
            name: name,
            lote: lote,
            fecha_vencimiento: fecha_vencimiento,
            cantidad: cantidad,
            precio_compra: precio_compra,
            iva: iva,
            descuento: descuento,
            precio_venta_caja: precio_venta_caja,
            precio_venta_blister: precio_venta_blister,
            precio_venta_unidad: precio_venta_unidad,
            ingresaComoObsequio: ingresaComoObsequio,
        };


        Livewire.emit('submitEvent', dataIngresoProducto)

    });


});



function calcularPreciosVenta()
{

    var disponible_blister = document.getElementById('input_disponible_blister');
    var disponible_unidad = document.getElementById('input_disponible_blister');

    var precio_compra = parseFloat(document.getElementById('precio_compra').value);
    var iva = parseFloat(document.getElementById('iva').value);
    var descuento = parseFloat(document.getElementById('descuento').value);

    var precio_venta_caja = document.getElementById('precio_venta_caja');
    var precio_venta_blister = document.getElementById('precio_venta_blister');
    var precio_venta_unidad = document.getElementById('precio_venta_unidad');

    var ganancia_por_caja = parseFloat(document.getElementById('porcentaje_por_caja').value);
    var ganancia_por_blister = parseFloat(document.getElementById('porcentaje_por_blister').value);
    var ganancia_por_unidad = parseFloat(document.getElementById('porcentaje_por_unidad').value);

    var contenido_interno_blister = parseFloat(document.getElementById('contenido_interno_blister_add').value);
    var contenido_interno_unidad = parseFloat(document.getElementById('contenido_interno_unidad_add').value);

    if(contenido_interno_blister > 0 && !isNaN(contenido_interno_blister)){

        var costo_compra_por_blister = precio_compra / contenido_interno_blister;
        precio_venta_blister.value = Math.round(calcularPrecioVenta(iva, costo_compra_por_blister, descuento, ganancia_por_blister));
    }

    if(contenido_interno_unidad > 0 && !isNaN(contenido_interno_unidad)){

        var costo_compra_por_unidad = precio_compra / contenido_interno_unidad;
        precio_venta_unidad.value = Math.round(calcularPrecioVenta(iva, costo_compra_por_unidad, descuento, ganancia_por_unidad));
    }



    precio_venta_caja.value = Math.round(calcularPrecioVenta(iva, precio_compra, descuento, ganancia_por_caja));


}



function calcularPrecioVenta(iva, precio_compra, descuento, ganancia)
{

    if (
        typeof iva === 'number' && iva >= 0 &&
        typeof precio_compra === 'number' && precio_compra >= 0 &&
        typeof descuento === 'number' && descuento >= 0 &&
        typeof ganancia === 'number' && ganancia >= 0
    ) {
        // Calcular el precio de venta

        const precioConGanancia = Math.floor(parseFloat(precio_compra) * (1 + parseFloat(ganancia) / 100));
        const precioConIVA = Math.floor(precioConGanancia * (1 + parseFloat(iva) / 100));
        const precioConDescuento = Math.floor(precioConIVA * (1 - parseFloat(descuento) / 100));
        const precioFinal = precioConDescuento;

        return precioFinal;
    } else {
        // Manejar caso de variables no válidas
        console.error("Error: Todas las variables deben ser números positivos.");
        return null;
    }

}



function validarFechaVencimiento() {
    // Obtener el valor de la fecha del input
    var fechaVencimientoInput = document.getElementById('fecha_vencimiento');
    var fechaSeleccionada = fechaVencimientoInput.value;

    // Obtener la fecha actual
    var fechaActual = new Date();

    // Convertir la fecha del input a un objeto Date
    var fechaInput = new Date(fechaSeleccionada);

    // Obtener el año actual y sumarle 3 años
    var maximoAnioPermitido = fechaActual.getFullYear() + 5;

    // Validar que la fecha sea superior a la fecha actual
    if (fechaInput < fechaActual) {
        fechaVencimientoInput.classList.add('is-invalid');
      return;
    }

    // Validar que la fecha no supere 3 años más que el año actual
    if (fechaInput.getFullYear() > maximoAnioPermitido) {
        fechaVencimientoInput.classList.add('is-invalid');
      return;
    }

    fechaVencimientoInput.classList.remove('is-invalid');

    // Si la fecha cumple ambas condiciones, puedes realizar otras acciones necesarias
    // ...

  }

  function validarCantidad() {
    // Obtén el valor del input
    var cantidadInput = document.getElementById('cantidad');
    var valor = cantidadInput.value;


    // Verifica si el valor es mayor a 0 y es un número
    if (valor > 0 && !isNaN(valor)) {
        // Si es válido, quita la clase is-invalid
        cantidadInput.classList.remove('is-invalid');
    } else {
        // Si no es válido, agrega la clase is-invalid
        cantidadInput.classList.add('is-invalid');
    }
}

function validarIva() {
    // Obtén el valor del input
    var ivaInput = document.getElementById('iva');
    var valor = ivaInput.value;


    // Verifica si el valor es mayor a 0 y es un número
    if (valor > 0 && !isNaN(valor)) {
        // Si es válido, quita la clase is-invalid
        ivaInput.classList.remove('is-invalid');
    } else {
        // Si no es válido, agrega la clase is-invalid
        ivaInput.classList.add('is-invalid');
    }
}


