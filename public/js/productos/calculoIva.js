

function calcularIva(precioProducto, porcentajeIva) {

    // Verificar si los valores son 0 o null
    if (precioProducto === 0 || precioProducto === null || porcentajeIva === 0 || porcentajeIva === null) {
        return 0; // Retorna 0 si alguno de los valores es 0 o null
    }

    // Calcula el valor del IVA
    var ivaCaja = Math.round(precioProducto - (precioProducto / (1 + (porcentajeIva / 100))));

    return ivaCaja;
}
