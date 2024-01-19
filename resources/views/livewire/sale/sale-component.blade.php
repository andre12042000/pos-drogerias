<div>
@include('popper::assets')
<div > @include('includes.alert')</div>
    <div class="card">
        <div class="card-header">

            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                @if ($empresa->modal_aut_produc == 1)

                                @endif
                                <i class="bi bi-search mr-3 mt-2" style="cursor: pointer" data-toggle="modal" data-target="#searchproduct"></i>
                            </div>
                            <input type="text" class="form-control @if($error_search) is-invalid @endif" id="inlineFormInputGroup"
                                placeholder="Buscar producto por código" wire:model.lazy ="codigo_de_producto" wire:keydown.enter="searchProductCode" autofocus autocomplete="disabled"> <!--Buscador por código -->
                    </div>
                    @error('codigo_de_producto')
                                <span class="text-danger">{{ $message }}</span>
                    @enderror

            </div>
                </div>
                <div class="row">
                    <div class="col">
                       {{--  <p><strong>Descuento: $ {{ $total_descuento }}</strong></p> --}}
                        <p><strong>Total venta: $ {{number_format($total_venta, 0) }}</strong></p>

                    </div>
                  <!--   <div class="col">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="contado" name="contado"
                                class="custom-control-input" value="contado" wire:model.lazy = "tipo_transaccion">
                            <label class="custom-control-label" for="contado">Contado</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="credito" name="credito"
                                class="custom-control-input" value="credito" wire:model.lazy = "tipo_transaccion">
                            <label class="custom-control-label" for="credito">Crédito</label>
                        </div>
                    </div> -->
                    <div class="col">

                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cliente"
                                aria-label="Recipient's username with two button addons"
                                aria-describedby="button-addon4" value="{{ $client_name }}" disabled readonly>
                            <div class="input-group-append" id="button-addon4">
                                <button class="btn btn-outline-secondary" type="button"data-toggle="modal"
                                        data-target="#searchclient"><i class="bi bi-search"
                                        style="cursor: pointer" ></i></button>
                                <button class="btn btn-outline-secondary" type="button"data-toggle="modal" data-target="#clientmodal"><i
                                        class="bi bi-plus-circle-fill" style="cursor: pointer" ></i></button>
                            </div>
                        </div>


                    </div>
                </div>
            </div>



        </div>
    </div>

    <div class="card" style="height: 400px">
        <div class="card-body">
            <div class="table-responsive-md">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Código</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Precio Unit.</th>
                            <th scope="col">Cant.</th>
                            <th scope="col">Descuento</th>
                            <th scope="col">Total</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                       @forelse ($this->productssale as $key => $product )
                       <tr>
                            <th scope="row">{{$loop->iteration }}</th>
                            <td>{{ $product['code'] }}</td>
                            <td>{{ $product['name'] }}</td>
                            <td>$ {{number_format($product['price'],0)}}</td>
                            <td>{{ $product['quantity'] }}</td>
                            <td>{{ $product['discount'] }}</td>
                            <td>$ {{number_format($product['total'],0) }}</td>
                            <td>
                                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                    <div class="btn-group-sm mr-2" role="group" aria-label="First group">
                                    @include('popper::assets')
                                      {{--   <button type="button" class="btn btn-outline-primary"wire:click="addiscount({{ $key }})"><i class="bi bi-pencil-square" ></i></button> --}} <!-- Editar -->

                                        <button type="button" class="btn btn-outline-success"wire:click="addQuantity({{ $key }})"><i class="bi bi-plus-lg" ></i> <!-- Incrementar --></button>

                                        <button type="button" class="btn btn-outline-secondary" wire:click="downCantidad({{ $key }})"><i class="bi bi-dash-lg" ></i></button> <!-- Restar -->

                                        <button @popper(Eliminar) type="button" class="btn btn-outline-danger"wire:click="destroyItem({{ $key }})"><i class="bi bi-trash3"  ></i> </button> <!-- Eliminar -->

                                    </div>
                                </div>
                            </td>
                    </tr>
@if($product['price'] == 0)
<div class="modal" tabindex="-1"  @if ($productorandom) style="display:block" @endif>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title">Actualizar Precio</h5>
          <button type="button" class="close" data-dismiss="modal" wire:click='modalexit' aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-4 col-form-label">Precio Unitario  $</label>
                <div class="col-sm-8">
                  <input type="text" wire:model.lazy="nuevoprecio" id="nuevoprecio" name="nuevoprecio" class="form-control @if($nuevoprecio == '') @else @error('nuevoprecio') is-invalid @else is-valid @enderror @endif" id="exampleFormControlInput1" placeholder="Precio unitario del producto" autocomplete="off" autofocus>
                  @error('nuevoprecio')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" wire:click='modalexit' data-ds-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-success" wire:click='precioproducto'>Actualizar</button>
        </div>
      </div>
    </div>
  </div>
          @endif
                       @empty
                       <tr>
                        <td colspan="8"><p>Agregue un producto para empezar...</p></td>
                       </tr>


                       @endforelse

                    </tbody>
                </table>
            </div>

        </div>
        <div class="card-footer">

            <x-adminlte-button label="" theme="success" icon="fas fa-check" class="float-right ml-3" wire:click="pay" />
{{--             <x-adminlte-button label="" title="Abrir Caja" theme="primary" icon="fas fa-cash-register" class="float-right" wire:click="abrirCajon" />
 --}}        </div>
        @include('modals.sale.discount')
        @include('modals.sale.pay')

 <script>
        // Verificar la condición
        @if ($empresa->modal_aut_produc == "1")
            $(document).ready(function() {
                // Abrir el modal
                $('#searchproduct').modal('show');
            });
        @endif
</script>
    </div>

</div>

