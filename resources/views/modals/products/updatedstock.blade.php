<div id="stockModal" class="modal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="exampleModalToggleLabel">Ajuste de inventario</h5>
                <button type="button" class="btn-close" aria-label="Close" onclick="cerrarModal()"></button>
            </div>
            <div id="contenidoModal">
                <div class="modal-body " style="background-color: #f5fbfb">
                    <div class="container">
                        <div class="row align-items-start">
                            <div class="col">


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
                                            <input type="text" class="form-control" id="inputIva" name="inputIva"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">% IVA</label>
                                        </div>
                                    </div>


                                </div>




                                <div class="row">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputStockMinimo"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">Stock mínimo </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" id="inputStockMaximo"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">Stock máximo</label>
                                        </div>
                                    </div>
                                </div>



                            </div>
                            <div class="col">
                                <div class="row mb-3">
                                    <div class="form-floating  col-4">
                                        <div class="form-floating">
                                            <select class="form-select"  id="disponible_caja" name="disponible_caja"
                                                aria-label="Floating label select example" wire:model.defer = 'disponible_caja' >
                                                <option selected>Seleccione una opción</option>
                                                <option value="1">Si</option>
                                                <option value="0">No</option>
                                            </select>
                                            <label for="floatingSelect">Disp. caja</label>
                                        </div>
                                    </div>

                                    <div class="form-floating  col-4">

                                        <div class="form-floating">
                                            <select class="form-select"  id="disponible_blister" name="disponible_blister"
                                                aria-label="Floating label select example" wire:model.defer = 'disponible_blister'>
                                                <option selected>Seleccione una opción</option>
                                                <option value="1">Si</option>
                                                <option value="0">No</option>
                                            </select>
                                            <label for="floatingSelect">Disp. blister</label>
                                        </div>


                                    </div>

                                    <div class="form-floating  col-4">

                                        <div class="form-floating">
                                            <select class="form-select"  id="disponible_unidad" name="disponible_unidad"
                                                aria-label="Floating label select example"  wire:model.defer = 'disponible_unidad'>
                                                <option selected>Seleccione una opción</option>
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
                                            <input type="text" class="form-control" id="inputCanCaja" name="inputCanCaja"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">Cantidad caja</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="inputCanBlister" name="inputCanBlister"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">Cantidad blister</label>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="inputCanUnidad" name="inputCanUnidad"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">Cantidad unidad</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row ">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="inputCosCaja" name="inputCosCaja"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">Costo caja</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="inputCosBlister" name="inputCosBlister"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">Costo blister</label>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="inputCosUnidad" name="inputCosUnidad"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">Costo unidad</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row ">
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="inputPreCaja" name="inputPreCaja"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">Precio venta caja</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="inputPreBlister" name="inputPreBlister"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">Precio venta blister</label>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="inputPreUnidad" name="inputPreUnidad"
                                                placeholder="name@example.com">
                                            <label for="floatingInput">Precio venta unidad</label>
                                        </div>
                                    </div>
                                </div>
 <a class="btn btn-outline-success float-end mt-4">Actualizar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
