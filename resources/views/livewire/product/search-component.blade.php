<div x-data>
    @include('popper::assets')
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <div class="input-group  float-right col-5">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                    <input @popper(Buscador) type="text" class="form-control" placeholder="Buscar producto"
                        aria-label="Username" aria-describedby="basic-addon1" wire:model="buscar">
                </div>
                <button type="button" class="btn-close " data-dismiss="modal" id="closeModalButton" wire:click="cancel"
                    aria-label="Close"></button>

            </div>
            <div class="modal-body ">
                <table class="table table-striped" id="tabProducts">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Laboratorio</th>
                            <th>P. Caja</th>
                            <th>P. Blister</th>
                            <th>P. Unidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->code }} </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ Illuminate\Support\Str::limit($product->laboratorio->name, 15) }}</td>
                                <td class="text-end table-success"><a style="cursor: pointer" title="Cantidad presentación: {{ $product->contenido_interno_caja }}"
                                        wire:click="selectProduct('caja', {{ $product }}, {{ $product->precio_caja }})">{{ $product->cliente }}</a>
                                </td>
                                <td class="text-end table-warning"><a style="cursor: pointer"
                                        wire:click="selectProduct('blister', {{ $product }}, {{ $product->precio_blister }})">{{ $product->tecnico }}
                                    </a></td>
                                <td class="text-end table-danger"><a style="cursor: pointer"
                                        wire:click="selectProduct('unidad', {{ $product }}, {{ $product->precio_unidad }})">{{ $product->distribuidor }}</a>
                                </td>
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

    boton.addEventListener("click", function(evento) {
        alert("su precio es de cliente");
    });
</script>
