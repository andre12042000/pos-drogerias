<div class="card" style="height: 400px;">
    <div class="card-header">

        <div class="row g-2">
            <div class="col-md">
              <div class="form-floating">
                <input type="text" class="form-control" id="buscarProducto">
                <label for="buscarProducto">Buscar producto</label>
              </div>
            </div>
            <div class="col-md">
              <div class="form-floating">
                <select class="form-select" id="filtrarProductoCategoria" aria-label="Floating label select example" wire:model = 'category'>
                  <option value="">Seleccione una opción</option>
                  @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ ucwords($categoria->name) }}</option>
                  @endforeach
                </select>
                <label for="filtrarProductoCategoria">Filtrar por categoría</label>
              </div>
            </div>
          </div>
    </div>
    <div class="card-body" style="overflow-y: auto;">

        <ul class="list-group">
            @forelse ($products as $product)
                <li class="list-group-item d-flex justify-content-between align-items-center product-card"
                    style="cursor: pointer" onclick="addToCart({{ json_encode($product) }})">
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

<script>
    let cart = [];

    function addToCart(product) {
        // Verificar si el producto ya está en el carrito
        let productIndex = cart.findIndex(item => item.id === product.id);


        if (productIndex !== -1) {
            // Si el producto ya está en el carrito, incrementar la cantidad
            cart[productIndex].cantidad++;
            cart[productIndex].total = cart[productIndex].cantidad * cart[productIndex].precio_venta;
        } else {
            // Si el producto no está en el carrito, agregarlo con una cantidad inicial de 1
            cart.push({
                id: product.id,
                name: product.name,
                cantidad: 1,
                precio_venta: product.precio_caja,
                total: product.precio_caja // La sumatoria inicial es el precio de venta
            });
        }

        updateCartView();
    }


</script>



