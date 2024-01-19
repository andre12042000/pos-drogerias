<div class="mt-3">
@section('title', 'Detalles Producto ')
    <div class="container ">
        <div class="row">
          <div class="col-4">
            <div class="card">
                <div class="card-header">
                  <strong>Detalles</strong>

                </div>
                <div class="card-body">
                <div class="row">


                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="nombre"><strong>Producto</strong></label>
                                    <p>{{$producto->name}}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Codigo</strong></label>
                                    <p> {{$producto->code }}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Precio compra</strong></label>
                                    <p>{{$producto->last_price}}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Stock</strong></label>
                                    <p>{{$producto->stock}}</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <label class="form-control-label" for="num_compra"><strong>Imagen</strong></label>
                                    <p>
                                    @if($producto->image)
                                        <img src="{!! Config::get('app.URL') !!}/storage/{{ $producto->image }}" width="50px"
                                            height="50px">

                                    @else
                                        <img src="{!! Config::get('app.URL') !!}/storage/livewire-tem/sin_image_product.jpg"
                                            width="50px">
                                    @endif
                                    </p>
                                </li>

                            </ul>



                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary " type="button" wire:click="doAction(1)">Graficos</button>
                        <button class="btn btn-outline-primary" type="button" wire:click="doAction(2)">Historia Ventas</button>
                        <button class="btn btn-outline-primary" type="button" wire:click="doAction(3)">Historia Compras</button>
                      </div>
                </div>
            </div>
           </div>
          <div class="col-8">
            @if($action == 1)
            <div class="card">
                @include('livewire.product.subform.graficos')
            </div>

            @elseif($action == 2)
            <div class="card">
                @include('livewire.product.subform.ventas')
            </div>

            @elseif($action == 3)

            <div class="card">
                @include('livewire.product.subform.compras')
            </div>

            @endif

          </div>
        </div>
      </div>
</div>
