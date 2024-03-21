<div class="card">
    <div class="card-header">

        <div class="row g-2">
            <div class="col-md">
              <div class="form-floating">
                <input type="email" class="form-control" id="floatingInputGrid" wire:model = 'search'>
                <label for="floatingInputGrid">Buscar producto</label>
              </div>
            </div>
            <div class="col-md">
              <div class="form-floating">
                <select class="form-select" id="floatingSelectGrid" aria-label="Floating label select example" wire:model = 'category'>
                  <option value="">Seleccione una opción</option>
                  @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ ucwords($categoria->name) }}</option>
                  @endforeach
                </select>
                <label for="floatingSelectGrid">Filtrar por categoría</label>
              </div>
            </div>
          </div>
    </div>
    <div class="card-body" style="overflow-y: auto;">

        <ul class="list-group">
            @forelse ($products as $product)
                <li class="list-group-item d-flex justify-content-between align-items-center product-card"
                    style="cursor: pointer">
                    <div class="d-flex align-items-center">
                        <img src="{{ $product->image_url }}" class="img-thumbnail mr-2" alt="Producto"
                            style="width: 80px; height: auto;">
                            <span><strong>{{ ucwords(strtolower($product->name)) }}</strong></span>

                    </div>
                    <span><small class="text-muted">$ {{ number_format($product->precio_caja, 0) }}</small></span>
                </li>
            @empty
                <!-- En caso de que no haya productos -->
                <li class="list-group-item">No hay productos disponibles.</li>
            @endforelse
        </ul>

        {{ $products->links() }}

    </div>
</div>


<style>
    .product-card:hover {
        background-color: #f8f9fa;
        /* Cambiar el color de fondo al pasar el mouse */
        transition: background-color 0.3s ease;
        /* Agregar una transición suave */
        opacity: 0.8;
        /* Cambiar la opacidad al pasar el mouse */
    }
</style>
