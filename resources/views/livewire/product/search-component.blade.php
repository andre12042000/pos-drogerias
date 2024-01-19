<div x-data>
    @include('popper::assets')
<div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header bg-success">
    <div class="input-group  float-right col-5">
    <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input @popper(Buscador) type="text" class="form-control" placeholder="Buscar producto"
                            aria-label="Username" aria-describedby="basic-addon1" wire:model="buscar">
                            </div>
        <button type="button" class="btn-close " data-dismiss="modal" id="closeModalButton" wire:click="cancel" aria-label="Close"></button>

      </div>
      <div class="modal-body ">
    <table class="table table-striped" id="tabProducts">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Stock</th>
                <th>P. Cliente</th>
                <th>P. Técnico</th>
                <th>P. Mayorista</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->stock }}</td>
                    <td class="text-end table-success"><a  style="cursor: pointer" wire:click="selectProduct({{ $product}}, {{$product->sell_price}})">{{ $product->cliente }}</a></td>
                    <td class="text-end table-warning"><a  style="cursor: pointer" wire:click="selectProduct({{ $product }}, {{$product->sell_price_tecnico}})">{{ $product->tecnico }} </a></td>
                    <td class="text-end table-danger"><a  style="cursor: pointer" wire:click="selectProduct({{$product }}, {{$product->sell_price_distribuidor}})">{{ $product->distribuidor }}</a></td>
                </tr>

            @empty
                <tr>
                    <td colspan="4">
                        <p>No se encontraron registros...</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="modal-footer mt-2">
    <nav class="" aria-label="">
                <ul class="pagination">
                    {{ $products->links() }}
                </ul>
            </nav>
      </div>


    </div>
  </div>
</div>
</div>

<script>
    const boton = document.querySelector(".cliente");

boton.addEventListener("click", function(evento){
	alert("su precio es de cliente");
});
</script>
