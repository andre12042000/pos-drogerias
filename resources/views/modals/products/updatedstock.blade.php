<div id="stockModal" class="modal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="exampleModalToggleLabel">Ajuste de inventario inicial</h5>
                <button type="button" class="btn-close" aria-label="Close" onclick="cerrarModal()"></button>
            </div>
            <div id="contenidoModal">
                <div class="modal-body " style="background-color: #f5fbfb">
                    <div class="container">
                        <div class="row align-items-start">
                            <div class="col">

                                <input type="hidden" class="form-control" id="inputId"
                                                name="inputId" placeholder="name@example.com">

                                <div class="row">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="inputCodigo"
                                                name="inputCodigo" placeholder="name@example.com">
                                            <label for="floatingInput">Código</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="inputName" name="inputName"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">Nombre / Descripción</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="row ">
                                    <div class="col">
                                        <div class="form-floating  mb-3">
                                            <select class="form-select" id="categoriaSelect" name="categoriaSelect"
                                                aria-label="Floating label select example">
                                            </select>
                                            <label for="floatingSelect">Categoría</label>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="subcategoriaSelect"
                                                name="subcategoriaSelect" aria-label="Floating label select example">
                                            </select>
                                            <label for="floatingSelect">Subcategoría</label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row ">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="ubicacionesSelect" name="ubicacionesSelect"
                                                aria-label="Floating label select example">
                                            </select>
                                            <label for="floatingSelect">Ubicación</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="presentacionesSelect"
                                                name="presentacionesSelect" aria-label="Floating label select example">
                                            </select>
                                            <label for="floatingSelect">Presentación</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row ">


                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <select class="form-select" id="laboratoriosSelect"
                                                name="laboratoriosSelect" aria-label="Floating label select example">
                                            </select>
                                            <label for="floatingSelect">Laboratorio</label>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputIva" name="inputIva" value="0"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">% IVA</label>
                                        </div>
                                    </div>


                                </div>




                                <div class="row">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputStockMinimo"
                                                placeholder="name@example.com" value="0" min="0">
                                            <label for="floatingInput">Stock mínimo </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputStockMaximo"
                                                placeholder="name@example.com" value="0" min="0">
                                            <label for="floatingInput">Stock máximo</label>
                                        </div>
                                    </div>
                                </div>



                            </div>
                            <div class="col">
                                <div class="row mb-3">
                                    <div class="form-floating  col-4">
                                        <div class="form-floating">
                                            <select class="form-select" id="disponibleCajaSelect"
                                                name="disponibleCajaSelect" disabled
                                                aria-label="Floating label select example">
                                                <option value="1" selected>Si</option>
                                                <option value="0">No</option>
                                            </select>
                                            <label for="floatingSelect">Disp. caja</label>
                                        </div>
                                    </div>

                                    <div class="form-floating  col-4">

                                        <div class="form-floating">
                                            <select class="form-select" id="disponibleBlisterSelect"
                                                name="disponibleBlisterSelect"
                                                aria-label="Floating label select example" onchange="verificarSeleccionBlisterSelect()">
                                                <option value="Seleccionar">Seleccione una opción</option>
                                                <option value="1">Si</option>
                                                <option value="0">No</option>
                                            </select>
                                            <label for="floatingSelect">Disp. blister</label>
                                        </div>


                                    </div>

                                    <div class="form-floating  col-4">

                                        <div class="form-floating">
                                            <select class="form-select" id="disponibleUnidadSelect"
                                                name="disponibleUnidadSelect" onchange="verificarSeleccionUnidadSelect()"
                                                aria-label="Floating label select example">
                                                <option value="Seleccionar">Seleccione una opción</option>
                                                <option value="1">Si</option>
                                                <option value="0">No</option>
                                            </select>
                                            <label for="floatingSelect">Disp. unidad</label>
                                        </div>

                                    </div>
                                </div>

                                <div class="row ">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="hidden" class="form-control" id="puntoReferenciaCaja"
                                                name="puntoReferenciaCaja" placeholder="name@example.com"
                                                value="1" disabled>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputBlisterPorCaja"
                                                name="inputBlisterPorCaja" placeholder="name@example.com" disabled>
                                            <label for="floatingInput">Blister * caja</label>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputUnidadesPorCaja"
                                                name="inputUnidadesPorCaja" placeholder="name@example.com" disabled>
                                            <label for="floatingInput">Unidades * caja</label>
                                        </div>
                                    </div>
                                </div>





                                <div class="row ">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputCostoPorCaja"
                                                name="inputCostoPorCaja" placeholder="name@example.com" disabled>
                                            <label for="floatingInput">Costo caja</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputCostoPorBlister"
                                                name="inputCostoPorBlister" placeholder="name@example.com" disabled>
                                            <label for="floatingInput">Costo blister</label>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputCostoPorUnidad"
                                                name="inputCostoPorUnidad" placeholder="name@example.com" disabled>
                                            <label for="floatingInput">Costo unidad</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row ">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputPrecioVentaCaja"
                                                name="inputPrecioVentaCaja" placeholder="name@example.com" disabled>
                                            <label for="floatingInput">Precio venta caja</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputPrecioVentaBlister"
                                                name="inputPrecioVentaBlister" placeholder="name@example.com" disabled>
                                            <label for="floatingInput">Precio venta blister</label>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputPrecioVentaUnidad"
                                                name="inputPrecioVentaUnidad" placeholder="name@example.com" disabled>
                                            <label for="floatingInput">Precio venta unidad</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row ">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputStockActualEnCajas"
                                                name="inputStockActualEnCajas" placeholder="name@example.com" disabled>
                                            <label for="floatingInput">Stock caja</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputStockBlister"
                                                name="inputStockBlister" placeholder="name@example.com" disabled>
                                            <label for="floatingInput">Stock blister</label>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputStockUnidad"
                                                name="inputUnidadesPorCaja" placeholder="name@example.com" disabled>
                                            <label for="floatingInput">Stock unidad</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row ">
                                    <div class="col">

                                    </div>
                                    <div class="col">

                                    </div>

                                    <div class="col">
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-outline-success float-end mt-4" onclick="actualizarInventario()" id="btnActualizar" disabled>Actualizar</button>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('js/productos/ajusteInventarioInicial.js') }}">
</script>


