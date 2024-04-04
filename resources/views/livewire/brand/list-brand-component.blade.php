  <div>
    @include('popper::assets')
      <div class="card card-info mt-1">
          <div class="card-header">
              <div class="row">
                  <div class="col-sm-6">
                      <h3>Marcas</h3>
                  </div>
                  <div class="col-sm-6">
                  <div class="input-group float-right">
                  <select  wire:model="cantidad_registros" class="form-select col-sm-2 mr-2" aria-label="Default select example">
                        <option value="10"> 10 </option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                </select>
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar marca" aria-label="Username"
                            aria-describedby="basic-addon1"  wire:model = "buscar">
                            <button type="button" class="btn btn-outline-light float-right ml-2" data-toggle="modal" data-target="#brandmodal" >Nueva marca <i class="las la-plus-circle"></i></button>
                    </div>
                    </div>
              </div>
          </div>
          <div class="card-body">
              <table class="table table-striped" id="tabMarcas">
                  <thead>
                      <tr>
                          <th>Nombre</th>
                          <th class="text-center">Acciones</th>
                      </tr>
                  </thead>
                  <tbody>
                      @forelse ($brands as $brand)
                      <tr>
                          <td> {{ ucwords($brand->name) }}</td>

                          <td class="text-center">
                              <a @popper(Editar) class="btn btn-outline-success btn-sm" href="#" role="button" data-toggle="modal" data-target="#brandmodal" wire:click="sendData( {{ $brand }} )"><i class="bi bi-pencil-square"></i></a>
                              <button @popper(Eliminar) class="btn btn-outline-danger btn-sm" wire:click="destroy( {{ $brand->id }} )"><i class="bi bi-trash3"></i></button>


                          </td>
                      </tr>

                      @empty
                      <tr>
                          <td colspan="2">
                              <p>No se encontraron registros...</p>
                          </td>
                      </tr>
                      @endforelse
                  </tbody>
              </table>
          </div>
          <div class="card-footer ">
            <nav class="" aria-label="">
                <ul class="pagination">
                  {{ $brands->links() }}
                </ul>
              </nav>

        </div>
      </div>
  </div>

  <script>
    function setFocusName() {
        $('#name').focus();
    }

    $("#brandmodal").on('shown.bs.modal', function() {
        setTimeout(setFocusName, 0);
    });
</script>


