<div id="editarProductoModal" class="modal">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title" id="exampleModalToggleLabel">Editar producto</h4>
                <button type="button" class="btn-close" aria-label="Close" onclick="cerrarModal()"></button>
            </div>
            <div id="contenidoModal">
                @livewire('product.edit-product-component')
            </div>
        </div>
    </div>
</div>


{{-- <script src="{{ asset('js/productos/ajusteInventarioInicial.js') }}"> --}}
</script>


