function verificarSeleccionBlisterSelect() {
    var selectElement = document.getElementById("disponibleBlisterSelect");
    var input1Element = document.getElementById("inputBlisterPorCaja");
    var input2Element = document.getElementById("inputStockBlister");
    var input3Element = document.getElementById("inputCosBlister");
    var input4Element = document.getElementById("inputPreBlister");

    if (selectElement.value !== "1") {
        // Si el valor del select no es 1, desactivar los inputs
        input1Element.disabled = true;
        input2Element.disabled = true;
        input3Element.disabled = true;
        input4Element.disabled = true;
    } else {
        // Si el valor del select es 1, habilitar los inputs
        input1Element.disabled = false;
        input2Element.disabled = false;
        input3Element.disabled = false;
        input4Element.disabled = false;
    }

    this.desbloquearInputsIniciales();
}

function verificarSeleccionUnidadSelect() {
    var selectElement = document.getElementById("disponibleUnidadSelect");
    var input1Element = document.getElementById("inputCanUnidad");
    var input2Element = document.getElementById("inputStockUnidad");
    var input3Element = document.getElementById("inputCosUnidad");
    var input4Element = document.getElementById("inputPreUnidad");

    if (selectElement.value !== "1") {
        // Si el valor del select no es 1, desactivar los inputs
        input1Element.disabled = true;
        input2Element.disabled = true;
        input3Element.disabled = true;
        input4Element.disabled = true;
    } else {
        // Si el valor del select es 1, habilitar los inputs
        input1Element.disabled = false;
        input2Element.disabled = false;
        input3Element.disabled = false;
        input4Element.disabled = false;
    }

    this.desbloquearInputsIniciales();
}

function desbloquearInputsIniciales()
{
    var select1 = document.getElementById("disponibleUnidadSelect");
    var select2 = document.getElementById("disponibleBlisterSelect");

    var input1 = document.getElementById("inputCanCaja");
    var input2 = document.getElementById("inputCosCaja");
    var input3 = document.getElementById("inputPreCaja");

    if (select1.value != "Seleccionar" && select2.value != "Seleccionar") {
        input1.disabled = false;
        input2.disabled = false;
        input3.disabled = false;

    }else{
        input1.disabled = true;
        input2.disabled = true;
        input3.disabled = true;

        input1.value = 0;
        input2.value = 0;
        input3.value = 0;
    }
}

function actualizarInventario()
{

//variables para ajustar el producto
    var id = document.getElementById("inputId").value;
    var code = document.getElementById("inputCodigo").value;
    var name = document.getElementById("inputName").value;
    var category_id = document.getElementById("categoriaSelect").value;
    var subcategoria_id = document.getElementById("subcategoriaSelect").value;
    var ubicacion_id = document.getElementById("ubicacionesSelect").value;
    var presentacion_id = document.getElementById("presentacionesSelect").value;
    var laboratorio_id = document.getElementById("laboratoriosSelect").value;
    var iva_product = parseInt(document.getElementById("inputIva").value, 10);
    var stock_min = parseInt(document.getElementById("inputStockMinimo").value, 10);
    var stock_max = parseInt(document.getElementById("inputStockMaximo").value, 10);
    var disponible_caja = document.getElementById("disponibleCajaSelect").value;
    var disponible_blister = document.getElementById("disponibleBlisterSelect").value;
    var disponible_unidad = document.getElementById("disponibleUnidadSelect").value;
    var contenido_interno_caja = parseInt(document.getElementById("puntoReferenciaCaja").value, 10);
    var contenido_interno_blister = parseInt(document.getElementById("inputBlisterPorCaja").value, 10);
    var contenido_interno_unidad = parseInt(document.getElementById("inputCanUnidad").value, 10);
    var costo_caja = parseInt(document.getElementById("inputCosCaja").value, 10);
    var costo_blister = parseInt(document.getElementById("inputCosBlister").value , 10);
    var costo_unidad = parseInt(document.getElementById("inputCosUnidad").value, 10);
    var precio_caja = parseInt(document.getElementById("inputPreCaja").value, 10);
    var precio_blister = parseInt(document.getElementById("inputPreBlister").value, 10);
    var precio_unidad = parseInt(document.getElementById("inputPreUnidad").value, 10);
    var valor_iva_caja = calcularIVA(iva_product, costo_caja);
    var valor_iva_blister = calcularIVA(iva_product, costo_blister);
    var valor_iva_unidad = calcularIVA(iva_product, costo_unidad);

    var cantidad_caja = parseInt(document.getElementById("inputCanCaja").value , 10);
    var cantidad_blister = parseInt(document.getElementById("inputStockBlister").value, 10);
    var cantidad_unidad = parseInt(document.getElementById("inputStockUnidad").value, 10);

    let datosInventario = {
        product_id: id,
        cantidad_caja:cantidad_caja,
        cantidad_blister:cantidad_blister,
        cantidad_unidad:cantidad_unidad,
    }


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
        costo_caja:costo_caja,
        costo_blister:costo_blister,
        costo_unidad:costo_unidad,
        precio_caja:precio_caja,
        precio_blister:precio_blister,
        precio_unidad:precio_unidad,
        valor_iva_caja:valor_iva_caja,
        valor_iva_blister:valor_iva_blister,
        valor_iva_unidad:valor_iva_unidad,
    }


    Livewire.emit('ajusteInventarioEvent', { dataProduct: datosProducto, datosInventario: datosInventario });



// Variables para ajustar el inventario


}

function calcularIVA(porcentajeIVA, monto_calcular) {

    if (isNaN(monto_calcular) || monto_calcular === "" || isNaN(porcentajeIVA) || porcentajeIVA === "") {
        return 0;
    }


    var iva = (parseFloat(monto_calcular) * parseFloat(porcentajeIVA)) / 100;


    return iva;
}
