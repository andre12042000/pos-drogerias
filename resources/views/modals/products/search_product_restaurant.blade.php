<div class="modal fade" id="searchproductrestaurant" data-bs-backdrop="statics" aria-labelledby="exampleModalLabel" data-keyboard="true" tabindex="-1"  aria-hidden="false">

    @livewire('product.components.search-product-component', ['key' => 'search-component-' . 'BuscarProductosComponent'])



</div>

<script>
    document.getElementById('closeModalButton').addEventListener('click', function() {
        $('#searchproductrestaurant').modal('hide');
    });
</script>
