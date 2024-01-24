<div id="stockModal" class="modal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
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
                                        <input type="text" class="form-control" id="inputCodigo" name="inputCodigo" placeholder="name@example.com">
                                        <label for="floatingInput">Código</label>
                                      </div>
                                </div>
                                <div class="col">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="inputName" name="inputName" placeholder="name@example.com">
                                        <label for="floatingInput">Nombre / Descripción</label>
                                      </div>
                                </div>
                              </div>


                              <div class="row">
                                <div class="col">
                                    <div class="form-floating">
                                        <select class="form-select" id="categoriaSelect" name="categoriaSelect" aria-label="Floating label select example">
                                        </select>
                                        <label for="floatingSelect">Categoría</label>
                                      </div>
                                </div>
                                <div class="col">
                                    <div class="form-floating">
                                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                          <option selected>Open this select menu</option>
                                          <option value="1">One</option>
                                          <option value="2">Two</option>
                                          <option value="3">Three</option>
                                        </select>
                                        <label for="floatingSelect">Works with selects</label>
                                      </div>
                                </div>
                              </div>


                              <div class="row">
                                <div class="col">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="inputStockMinimo" placeholder="name@example.com">
                                        <label for="floatingInput">Stock mínimo </label>
                                      </div>
                                </div>
                                <div class="col">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="inputStockMaximo" placeholder="name@example.com">
                                        <label for="floatingInput">Stock máximo</label>
                                      </div>
                                </div>
                              </div>



                          </div>
                          <div class="col">
                            One of three columns
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
