
var productosParaVenta = [];

var cantidadProducto = 0;
var precioUnitario = 0;
var totalPrecioProduct = 0;

function actualizarTabla() {

    // Obtener la referencia de la tabla
    var tabla = document.getElementById('tablaProductos');

    while (tabla.rows.length > 0) {
        tabla.deleteRow(0);
    }



    // Iterar sobre el array y agregar filas a la tabla
    productosParaVenta.forEach(function(producto) {
        var fila = tabla.insertRow();
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


/*-----------------Eventos escucha livewire ---------------------------------------------*/
window.addEventListener('agregarProductoAlArrayCode', event => {

    var producto = event.detail.producto;
    var cantidadIngresada = parseInt(document.getElementById('cantidadInput').value);

    // Crear un modal para preguntar la cantidad
    var cantidadModal = new bootstrap.Modal(document.getElementById('cantidadModal'));
    cantidadModal.producto = producto; //Pasamos la variable producto al modal


    cantidadModal.show(); //abrimos el modal

    var selectPresentacion = document.getElementById('selectPresentacion');

    // Deshabilitar todas las opciones
    var opciones = selectPresentacion.options;
    for (var i = 0; i < opciones.length; i++) {
        opciones[i].disabled = true;
    }

    // Habilitar las opciones según los valores de las variables del producto
    if (producto.disponible_caja) {
        // Habilitar la opción con valor "disponible_caja"
        selectPresentacion.querySelector('[value="disponible_caja"]').disabled = false;
    }

    if (producto.disponible_blister) {
        // Habilitar la opción con valor "disponible_blister"
        selectPresentacion.querySelector('[value="disponible_blister"]').disabled = false;
    }

    if (producto.disponible_unidad) {
        // Habilitar la opción con valor "disponible_unidad"
        selectPresentacion.querySelector('[value="disponible_unidad"]').disabled = false;
    }

    // Seleccionar automáticamente la opción si solo hay una opción disponible
    var opcionesDisponibles = Array.from(opciones).filter(opcion => !opcion.disabled);
    if (opcionesDisponibles.length === 1) {
        opcionesDisponibles[0].selected = true;


    var presentacionSeleccionada = selectPresentacion.value;

    var precioUnitario;

    switch (presentacionSeleccionada) {
        case 'disponible_caja':
            precioUnitario = producto.precio_caja;
            break;
        case 'disponible_blister':
            precioUnitario = producto.precio_blister;
            break;
        case 'disponible_unidad':
            precioUnitario = producto.precio_unidad;
            break;
        default:
            // Manejar el caso por defecto o mostrar un mensaje de error si es necesario
            break;
    }

     // Mostrar el precio unitario en el input correspondiente
     var totalPrecioCompraInput = document.getElementById('totalPrecioCompraInput');
     var precioUnitarioInput = document.getElementById('precioUnitarioInput');
     precioUnitarioInput.value = precioUnitario;
     totalPrecioProducts = precioUnitario * cantidadIngresada;

     totalPrecioCompraInput.value = totalPrecioProducts;
    }


    var nuevoProducto = {
        item: 3,
        id_producto: producto.id,
        codigo: producto.code,
        descripcion: producto.name,
        forma: 'Caja',
        precio_unitario: producto.precio_caja,
        cantidad: 1,
        descuento: 0,
        subtotal: producto.precio_caja,
    }

    productosParaVenta.push(nuevoProducto);

    actualizarTabla();

   // console.log(event.detail.producto);
   // alert('Name updated to: ' + event.detail.product);
})


function obtenerPrecioTotal(cantidad, precioUnitario)
{
    var obtenerPrecioTotal = cantidad * precioUnitario

    return obtenerPrecioTotal

}



/*-----------------------Fin eventos livewire ---------------------------------------*/
