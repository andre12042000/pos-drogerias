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
                    <select class="form-select" id="filtrarProductoCategoria" aria-label="Floating label select example"
                        wire:model = 'category'>
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
                    style="cursor: pointer" onclick="abrirModalCantidad({{ json_encode($product) }})">
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
@include('modals.sale.forma_cantidad')


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
    let producto_seleccionado;

    document.getElementById("precioUnitarioInput").addEventListener("input", calcularPrecioTotal);
    document.getElementById("cantidadInput").addEventListener("input", calcularPrecioTotal);
    var precioUnitarioInput = document.getElementById("precioUnitarioInput");
    var selectPresentacion = document.getElementById("selectPresentacion");


    function abrirModalCantidad(producto) {
        producto_seleccionado = producto;

        document.getElementById("cantidadModal").style.display = "block";

        deshabilitarOptionsSelect(
        producto_seleccionado); //Deshabilita los options que no esten disponibles de acuerdo al producto


    }


    selectPresentacion.addEventListener("change", function() {
        // Obtener el valor seleccionado en el select
        var opcionSeleccionada = selectPresentacion.value;

        // Actualizar el precio unitario según la opción seleccionada
        switch (opcionSeleccionada) {
            case "disponible_caja":
                precioUnitarioInput.value = producto_seleccionado.precio_caja;
                break;
            case "disponible_blister":
                precioUnitarioInput.value = producto_seleccionado.precio_blister;
                break;
            case "disponible_unidad":
                precioUnitarioInput.value = producto_seleccionado.precio_unidad;
                break;
            default:
                // Si no se selecciona ninguna opción válida, establecer el precio unitario en 0
                precioUnitarioInput.value = 0;
                break;
        }

        calcularPrecioTotal();
    });

    document.getElementById("agregarProductosArrayVenta").addEventListener("click", function() {
        // Llamamos a la función interna y le pasamos el producto
        addToCart(producto_seleccionado);
        producto_seleccionado = '';
    });

    function addToCart(producto) {
        var opcionSeleccionada = document.getElementById("selectPresentacion").value;

        // Obtener el precio total
        var precioTotal = parseFloat(document.getElementById("totalPrecioCompraInput").value);

        // Validar que se haya seleccionado algo en el select y que el precio total sea superior a 100
        if (opcionSeleccionada !== "" && precioTotal > 100) {
            // Realizar aquí las acciones que deseas ejecutar si la validación pasa
            let productIndex = cart.findIndex(item => item.id === producto.id && item.forma === opcionSeleccionada);

            if (productIndex !== -1) {
                // Si el producto ya está en el carrito, incrementar la cantidad
                cart[productIndex].cantidad++;
                cart[productIndex].total = cart[productIndex].cantidad * cart[productIndex].precio_venta;
            } else {
                // Si el producto no está en el carrito, agregarlo con una cantidad inicial de 1
                cart.push({
                    id: producto.id,
                    name: producto.name,
                    forma: opcionSeleccionada,
                    cantidad: parseInt(document.getElementById("cantidadInput").value),
                    precio_venta: parseFloat(document.getElementById("precioUnitarioInput").value),
                    total: parseFloat(document.getElementById("totalPrecioCompraInput")
                        .value) // La sumatoria inicial es el precio de venta
                });
            }

            producto = '';

            updateCartView();
            cerrarModalCantidadModal();

        } else {
            // Mostrar un mensaje de error o tomar alguna otra acción si la validación falla
            console.log(
                "La selección en el select es obligatoria y el precio total debe ser superior a 100.");
        }

    }

    function removeFromCart(productId) {
        // Filtrar el producto a eliminar del carrito
        cart = cart.filter(product => product.id !== productId);
        // Actualizar la vista del carrito
        updateCartView();
    }

    function updateCartView() {
        // Obtener la tabla y el cuerpo de la tabla
        let tableBody = document.querySelector('.order-list tbody');
        let noProductsMsg = document.getElementById('no-products-msg');

        // Limpiar la tabla antes de actualizarla
        tableBody.innerHTML = '';

        if (cart.length === 0) {
            // Mostrar el mensaje de "No hay productos en el carrito"
            noProductsMsg.style.display = 'block';
        } else {
            // Ocultar el mensaje de "No hay productos en el carrito"
            noProductsMsg.style.display = 'none';

            // Recorrer los productos en el carrito y agregarlos a la tabla
            cart.forEach(product => {

                let formaNombre = '';
                switch (product.forma) {
                    case 'disponible_caja':
                        formaNombre = 'Caja';
                        break;
                    case 'disponible_blister':
                        formaNombre = 'Blister';
                        break;
                    case 'disponible_unidad':
                        formaNombre = 'Unidad';
                        break;
                    default:
                        formaNombre = 'Desconocido';
                }


                let row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.name}</td>
                    <td>${formaNombre}</td>
                    <td class="text-center">${product.cantidad}</td>
                    <td class="text-end">$${product.precio_venta}</td>
                    <td class="text-end">$${product.total}</td>
                    <td>
                            <i class="bi bi-trash" onclick="removeFromCart(${product.id})" style="cursor:pointer;"></i> <!-- Icono de papelera -->

                    </td>
                `;
                tableBody.appendChild(row);
            });
        }
    }



    document.getElementById("btnIncrementar").addEventListener("click", function() {
        incrementarCantidad();
    });

    document.getElementById("btnDecrementar").addEventListener("click", function() {
        decrementarCantidad();
    });

    // Agregar event listener al input de cantidad
    document.getElementById("totalPrecioCompraInput").addEventListener("input", function() {
        calcularPrecioTotal();
    });



    function calcularPrecioTotal() {
        var precioUnitario = parseFloat(document.getElementById("precioUnitarioInput").value);
        var cantidad = parseFloat(document.getElementById("cantidadInput").value);
        var precioTotal = precioUnitario * cantidad;
        document.getElementById("totalPrecioCompraInput").value = precioTotal.toFixed(0);
    }

    function deshabilitarOptionsSelect(producto) {
        var selectPresentacion = document.getElementById("selectPresentacion");

        // Deshabilitar opciones según los precios del producto

        if (producto.precio_caja === 0) {
            var opcionCaja = document.querySelector('option[value="disponible_caja"]');
            opcionCaja.disabled = true;
        }
        if (producto.precio_blister === 0) {
            var opcionBlister = document.querySelector('option[value="disponible_blister"]');
            opcionBlister.disabled = true;
        }
        if (producto.precio_unidad === 0) {
            var opcionUnidad = document.querySelector('option[value="disponible_unidad"]');
            opcionUnidad.disabled = true;
        }
    }

    function incrementarCantidad() {
        var cantidadInput = document.getElementById("cantidadInput");
        var cantidad = parseInt(cantidadInput.value);
        cantidadInput.value = cantidad + 1;
        calcularPrecioTotal(); // Llama a la función para recalcular el precio total cuando se incrementa la cantidad
    }

    function decrementarCantidad() {
        var cantidadInput = document.getElementById("cantidadInput");
        var cantidad = parseInt(cantidadInput.value);
        if (cantidad > 1) { // Verifica que la cantidad no sea menor que 1
            cantidadInput.value = cantidad - 1;
            calcularPrecioTotal
                (); // Llama a la función para recalcular el precio total cuando se decrementa la cantidad
        }
    }

    /*----------------------------Agregar datos al array de pedidos ---------------------*/
</script>
