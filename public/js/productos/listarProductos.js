window.addEventListener("nuevo-producto", (event) => {
    const producto = event.detail.producto;
    Swal.fire({
        icon: "success",
        title: "Producto registrado correctamente",
        text: `Haz registrado correctamente el producto: ` + producto,
        showConfirmButton: false,
        timer: 3000,
    });
    setTimeout(() => {
        location.reload();
    }, 1500);
});

function abrirModal(
    product,
    categorias,
    subcategorias,
    presentaciones,
    ubicaciones,
    laboratorios
) {
    var modal = document.getElementById("stockModal");
    var contenidoModal = document.getElementById("contenidoModal");

    var tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);

    // Formatear la fecha en el formato yyyy-mm-dd
    var tomorrowFormatted = tomorrow.toISOString().split("T")[0];

    // Establecer la fecha mínima en el campo de fecha
    document.getElementById("fecha_vencimiento_ajuste_inventario").min =
        tomorrowFormatted;

    // Función para crear y añadir una opción a un select
    function agregarOpcion(select, value, text, selected = false) {
        var option = document.createElement("option");
        option.value = value;
        option.text = text;
        select.appendChild(option);
        if (selected) {
            option.selected = true;
        }
    }

    // Función para limpiar las opciones de un select
    function limpiarSelect(select) {
        select.innerHTML = "";
    }

    function llenarSelectPresentacion(
        select,
        array,
        selectedId,
        additionalAttributes
    ) {
        limpiarSelect(select);
        agregarOpcion(select, "", "Selecciona una opción");

        array.forEach(function (item) {
            // Crear un objeto de opción con atributos adicionales
            var optionAttributes = {
                value: item.id,
                text: item.name,
                selected: item.id === selectedId,
                // Agregar atributos adicionales
                por_caja: item.por_caja,
                por_blister: item.por_blister,
                por_unidad: item.por_unidad,
            };

            // Agregar la opción al select
            agregarOpcion(select, optionAttributes);
        });
    }

    function agregarOpcion(select, optionAttributes) {
        var option = document.createElement("option");
        option.value = optionAttributes.value;
        option.text = optionAttributes.text;
        option.selected = optionAttributes.selected || false;

        // Agregar atributos adicionales
        option.por_caja = optionAttributes.por_caja;
        option.por_blister = optionAttributes.por_blister;
        option.por_unidad = optionAttributes.por_unidad;

        select.appendChild(option);
    }

    // Llenar selects con datos
    llenarSelectPresentacion(
        document.getElementById("presentacionesSelect"),
        presentaciones,
        product.presentacion_id
    );

    // Mostrar los datos en el contenido del modal
    document.getElementById("inputId").value = product.id;
    document.getElementById("inputCodigo").value = product.code;
    document.getElementById("inputName").value = product.name;
    document.getElementById("inputStockMinimo").value = product.stock_min;

    //Pasamos datos a los select excepto a Presentacion

    var categoriaSelect = document.getElementById("categoriaSelect");
    var subcategoriaSelect = document.getElementById("subcategoriaSelect");
    var ubicacionesSelect = document.getElementById("ubicacionesSelect");
    var laboratoriosSelect = document.getElementById("laboratoriosSelect");

    // Limpiar opciones existentes en el select
    categoriaSelect.innerHTML = "";
    subcategoriaSelect.innerHTML = "";
    ubicacionesSelect.innerHTML = "";
    laboratoriosSelect.innerHTML = "";

    // Crear opciones para el select basadas en las categorías proporcionadas
    var defaultLaboratoriosOption = document.createElement("option");
    defaultLaboratoriosOption.value = "";
    defaultLaboratoriosOption.text = "Selecciona una opción";
    laboratoriosSelect.appendChild(defaultLaboratoriosOption);

    laboratorios.forEach(function (laboratorios) {
        var option = document.createElement("option");
        option.value = laboratorios.id;
        option.text = laboratorios.name;
        laboratoriosSelect.appendChild(option);
    });

    var defaultcategoriaOption = document.createElement("option");
    defaultcategoriaOption.value = "";
    defaultcategoriaOption.text = "Selecciona una opción";
    categoriaSelect.appendChild(defaultcategoriaOption);

    categorias.forEach(function (categoria) {
        var option = document.createElement("option");
        option.value = categoria.id;
        option.text = categoria.name;
        categoriaSelect.appendChild(option);
    });

    var defaultSubcategoriaOption = document.createElement("option");
    defaultSubcategoriaOption.value = "";
    defaultSubcategoriaOption.text = "Selecciona una opción";
    subcategoriaSelect.appendChild(defaultSubcategoriaOption);

    subcategorias.forEach(function (subcategoria) {
        var option = document.createElement("option");
        option.value = subcategoria.id;
        option.text = subcategoria.name;
        subcategoriaSelect.appendChild(option);
    });

    var defaultUbicacionOption = document.createElement("option");
    defaultUbicacionOption.value = "";
    defaultUbicacionOption.text = "Selecciona una opción";
    ubicacionesSelect.appendChild(defaultUbicacionOption);

    ubicaciones.forEach(function (ubicaciones) {
        var option = document.createElement("option");
        option.value = ubicaciones.id;
        option.text = ubicaciones.name;
        ubicacionesSelect.appendChild(option);
    });

    // Mostrar el modal
    modal.style.display = "block";
}

function cerrarModal() {
    // Cerrar el modal
    var modal = document.getElementById("stockModal");
    modal.style.display = "none";
}

window.addEventListener("alert-registro-actualizado", () => {
    Swal.fire({
        icon: "success",
        title: "Registro actualizado correctamente",
        showConfirmButton: false,
        timer: 1500,
    });
});

window.addEventListener("alert-error", (event) => {
    const { errorCode } = event.detail;
    console.log(event);
    Swal.fire({
        icon: "error",
        title: "Oops... ocurrió un error",
        text: `Código del error: ${errorCode}`,
    });
});



function modalAjuste(product, inventario) {

    var modal = document.getElementById("ajustarInventarioModal");
    var contenidoModal = document.getElementById("contenidoAjustarModal");

    var product_id = document.getElementById("product_id");
    var stock_cajas = document.getElementById("stock_cajas");
    var stock_blisters = document.getElementById("stock_blisters");
    var stock_unidades = document.getElementById("stock_unidades");

    stock_cajas.addEventListener("change", function() {

        if (stock_cajas.value !== '' && parseInt(stock_cajas.value) > 0) {

            if(product.disponible_blister > 0){
                stock_blisters.value = stock_cajas.value * product.contenido_interno_blister;
            }else{
                stock_blisters.value = 0;
            }

            if(product.disponible_unidad > 0){
                stock_unidades.value = stock_cajas.value * product.contenido_interno_unidad;
            }

        }

    });

    stock_cajas.value = inventario.cantidad_caja;
    product_id.value = product.id;

    if(product.disponible_blister === 0){
        stock_blisters.disabled = true;
        stock_blisters.value = "0";
    }else{
        stock_blisters.disabled = false;
        stock_blisters.value = inventario.cantidad_blister;
    }

    if(product.disponible_unidad === 0){
        stock_unidades.disabled = true;
        stock_unidades.value = "0";
    }else{
        stock_unidades.disabled = false;
        stock_unidades.value = inventario.cantidad_unidad;
    }



    // Mostrar el modal
    modal.style.display = "block";
}

function ajustarInventario()
{
    mostrarLoader();
    var product_id = document.getElementById("product_id")
    var stock_cajas = document.getElementById("stock_cajas");
    var stock_blisters = document.getElementById("stock_blisters");
    var stock_unidades = document.getElementById("stock_unidades");

    var error_cajas = document.getElementById("error_cajas");
    var error_blister = document.getElementById("error_blister");
    var error_unidades = document.getElementById("error_unidades");


    let errors = [];

    if(stock_cajas.value === ''){
        stock_cajas.classList.add('is-invalid');
        errors.push('El campo de stock de cajas no puede estar vacío.');
        error_cajas.style.display = "block";
        error_cajas.innerHTML = "El campo stock cajas no puede estar vacío.";
    }

    if(stock_blisters.value === ''){
        stock_blisters.classList.add('is-invalid');
        errors.push('El campo de stock de blisters no puede estar vacío.');
        error_blister.style.display = "block";
        error_blister.innerHTML = "El campo stock blisters no puede estar vacío.";
    }

    if(stock_unidades.value === ''){
        stock_unidades.classList.add('is-invalid');
        errors.push('El campo de stock de unidades no puede estar vacío.');
        error_unidades.style.display = "block";
        error_unidades.innerHTML = "El campo stock unidades no puede estar vacío.";
    }

    if (errors.length > 0) {
        ocultarLoader();
        return;
    }else{
        stock_cajas.classList.remove('is-invalid');
        stock_blisters.classList.remove('is-invalid');
        stock_unidades.classList.remove('is-invalid');

        error_cajas.style.display = "none";

    }


    Livewire.emit('actualizarInventarioEvent', {
        productId: product_id.value,
        stockCajas: stock_cajas.value,
        stockBlisters: stock_blisters.value,
        stockUnidades: stock_unidades.value
    });

}

function mostrarLoader() {
    var BtnAjustarInventario = document.getElementById("BtnAjustarInventario");
    var loader = BtnAjustarInventario.querySelector(".loader");
    BtnAjustarInventario.setAttribute("disabled", true);
    loader.style.display = "inline-block";
}

function ocultarLoader() {
    var BtnAjustarInventario = document.getElementById("BtnAjustarInventario");
    var loader = BtnAjustarInventario.querySelector(".loader");
    BtnAjustarInventario.removeAttribute("disabled");
    loader.style.display = "none";
}


function cerrarModalajsutes() {
    // Cerrar el modal
    var modal = document.getElementById("ajustarInventarioModal");

    modal.style.display = "none";
}



function modalEditarProducto(product, categorias, subcategorias, presentaciones, ubicaciones, laboratorios){

    var modal = document.getElementById("editarProductoModal");
    var contenidoModal = document.getElementById("editarProductoModal");

    /* Pasar valores a los inputs la logica de updte se hace en el script directamente en el modal */

    var id_product_edit = document.getElementById("id_product_edit");
    var code_edit = document.getElementById("code_edit");
    var name_edit = document.getElementById("name_edit");
    var iva_edit = document.getElementById("iva_edit");

    var stock_minimo_edit = document.getElementById("stock_minimo_edit");
    var stock_maximo_edit = document.getElementById("stock_maximo_edit");
    var disponible_blister_edit = document.getElementById("disponible_blister_edit");
    var disponible_unidad_edit = document.getElementById("disponible_unidad_edit");
    var blister_por_caja_edit = document.getElementById("blister_por_caja_edit");
    var unidad_por_caja_edit = document.getElementById("unidad_por_caja_edit");
    var CostoPorCajaEdit = document.getElementById("CostoPorCajaEdit");
    var CostoPorBlisterEdit = document.getElementById("CostoPorBlisterEdit");
    var CostoPorUnidadEdit = document.getElementById("CostoPorUnidadEdit");
    var PrecioVentaCajaEdit = document.getElementById("PrecioVentaCajaEdit");
    var PrecioVentaBlisterEdit = document.getElementById("PrecioVentaBlisterEdit");
    var PrecioVentaUnidadEdit = document.getElementById("PrecioVentaUnidadEdit");

    var estado_produc_active = document.getElementById("estado_produc_active");
    var estado_produc_inactive = document.getElementById("estado_produc_inactive");


   // var name_edit = document.getElementById("name_edit");

    id_product_edit.value = product.id;
    code_edit.value = product.code;
    name_edit.value = product.name;
    iva_edit.value = product.iva_product;
    stock_minimo_edit.value = product.stock_min;
    stock_maximo_edit.value = product.stock_max;
    disponible_blister_edit.value = product.disponible_blister;
    disponible_unidad_edit.value = product.disponible_unidad;
    blister_por_caja_edit.value = product.contenido_interno_blister;
    unidad_por_caja_edit.value = product.contenido_interno_unidad;
    CostoPorCajaEdit.value = product.costo_caja;
    CostoPorBlisterEdit.value = product.costo_blister;
    CostoPorUnidadEdit.value = product.costo_unidad;

    PrecioVentaCajaEdit.value = product.precio_caja;
    PrecioVentaBlisterEdit.value = product.precio_blister;
    PrecioVentaUnidadEdit.value = product.precio_unidad;


    if (product.status === "ACTIVE") {
        estado_produc_active.checked = true;
    } else if (product.status === "DESACTIVE") {
        estado_produc_inactive.checked = true;
    }



    Livewire.emit('editarProductEvent', product);

    modal.style.display = "block";
}

function verificarEstadoDisponibleBlister()
{

    var disponible_blister_edit = document.getElementById("disponible_blister_edit");
    var blister_por_caja_edit = document.getElementById("blister_por_caja_edit");
    var CostoPorBlisterEdit = document.getElementById("CostoPorBlisterEdit");
    var PrecioVentaBlisterEdit = document.getElementById("PrecioVentaBlisterEdit");
    console.log(disponible_blister_edit.value);

    if(disponible_blister_edit.value > 0){
        blister_por_caja_edit.disabled = false;
        PrecioVentaBlisterEdit.disabled = false;
        CostoPorBlisterEdit.disabled = false;
    }else{
        blister_por_caja_edit.disabled = true;
        PrecioVentaBlisterEdit.disabled = true;
        CostoPorBlisterEdit.disabled = true;
    }


}

function verificarEstadoDisponibleUnidad()
{
    var disponible_unidad_edit = document.getElementById("disponible_unidad_edit");
}
