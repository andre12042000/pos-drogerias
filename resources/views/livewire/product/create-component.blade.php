<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header bg-primary">
      <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de productos</strong> </h5>
      <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>
    </div>
    <div class="modal-body ">

      <div>
        @include('includes.alert')
        @include('popper::assets')

        <div class="form-row mb-0" x-data="{
        open: false,
        get isOpen() { return this.open },
        toggle() { this.open = !this.open },
     }">
          <div class="form-group col-md-4">
            <label for="inputEmail4">Código</label>
            <input type="text" id="code" name="code" class="form-control @if($codigo == '') @else @error('codigo') is-invalid @else is-valid @enderror @endif" id="inputEmail4" wire:model.lazy="codigo" placeholder="Código del producto" autocomplete="off">
            @error('codigo')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="inputPassword4">Producto</label>
            <input type="text" class="form-control @if($producto == '') @else @error('producto') is-invalid @else is-valid @enderror @endif" id="inputPassword4" wire:model.lazy="producto" placeholder="Nombre del producto" autocomplete="off">
            @error('producto')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>


          <div class="form-group col-md-4">
            <label for="inputEmail4">Categoría <i @popper(Crear categoría) class="bi bi-plus-circle-fill ml-1 text-success" style="cursor: pointer" @click="toggle()"></i> </label>
            <div x-show="isOpen">
              <input type="text" class="form-control @if($nuevacategoria == '') @else @error('nuevacategoria') is-invalid @else is-valid @enderror @endif mb-2" placeholder="Nueva categoría" wire:keydown.enter="guardarCategoria" wire:model.lazy="nuevacategoria" autocomplete="off">
              @error('nuevacategoria')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
            <select class="form-select @if($categoria == '') @else @error('categoria') is-invalid @else is-valid @enderror @endif" id="exampleFormControlSelect1" wire:model.lazy="categoria">
              <option value="">Seleccione una categoría</option>
              @foreach ($categories as $category)
              <option value="{{ $category->id }}">{{ ucwords($category->name) }}</option>
              @endforeach
            </select>
            @error('categoria')
            <span class="text-danger">{{ $message }}</span>
            @enderror

          </div>

        </div>






        <div class="form-row">
        <div class="form-group col-md-3">
            <label for="inputEmail4">% IVA</label>
            <input type="number" min:0 max:30 class="form-control @if($iva_product == '') @else @error('iva_product') is-invalid @else is-valid @enderror @endif" id="inputEmail4" wire:model.lazy="iva_product" placeholder="Ingresa el iva" autocomplete="off">
            @error('iva_product')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        <div class="form-group col-md-3">
            <label for="inputEmail4">Stock actual</label>
            <input type="number" class="form-control @if($stock_actual == '') @else @error('stock_actual') is-invalid @else is-valid @enderror @endif" id="inputEmail4" wire:model.lazy="stock_actual" placeholder="Productos en stock" autocomplete="off">
            @error('stock_actual')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group col-md-3">
            <label for="inputPassword4">Stock mínimo</label>
            <input type="number" class="form-control @if($stock_minimo == '') @else @error('stock_minimo') is-invalid @else is-valid @enderror @endif" id="inputPassword4" wire:model.lazy="stock_minimo" placeholder="Ej: 5" autocomplete="off">
            @error('stock_minimo')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="inputPassword4">Stock máximo</label>
            <input type="number" class="form-control @if($stock_maximo == '') @else @error('stock_maximo') is-invalid @else is-valid @enderror @endif" id="inputPassword4" wire:model.lazy="stock_maximo" placeholder="Ej: 20" autocomplete="off">
            @error('stock_maximo')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="inputEmail4">Precio de compra</label>
            <input type="number" id="preciocompra" name="preciocompra" class="form-control @if($precio_compra == '') @else @error('precio_compra') is-invalid @else is-valid @enderror @endif" id="inputEmail4" wire:model.lazy="precio_compra" placeholder="Ej: 50000">
            @error('precio_compra')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group col-md-3">
            <label for="inputPassword4">% Cliente</label>
            <input type="number" id="cliente" name="cliente" class="form-control" id="inputPassword4" placeholder="Ej: 40" onchange="calcular()" wire:model.lazy='cliente'>
          </div>
          <div class="form-group col-md-3">
            <label for="inputPassword4">% Técnico</label>
            <input type="number" id="tecnico" name="tecnico" class="form-control" id="inputPassword4" placeholder="Ej: 30" onchange="calcular()" wire:model.lazy='tecnico'>
          </div>
          <div class="form-group col-md-3">
            <label for="inputPassword4">% Distribuidor</label>
            <input type="number" id="distribuidor" name="distribuidor" class="form-control" id="inputPassword4" placeholder="Ej: 15" onchange="calcular()" wire:model.lazy='distribuidor'>
          </div>

        </div>

        <div class="row">
          <div class="form-group col-md-4">
            <label for="inputPassword4">Precio Cliente</label>
            <input type="number" id="precioventacliente" name="precioventacliente" class="form-control @if($precioventacliente == '') @else @error('precioventacliente') is-invalid @else is-valid @enderror @endif" id="inputPassword4" wire:model.lazy="precioventacliente" placeholder="Ej: 75000" readonly>
            @error('precioventacliente')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group col-md-4">
            <label for="inputPassword4">Precio Técnico</label>
            <input type="number" id="precioventatecnico" name="precioventatecnico" class="form-control @if($precioventatecnico == '') @else @error('precioventatecnico') is-invalid @else is-valid @enderror @endif" id="inputPassword4" wire:model.lazy="precioventatecnico" placeholder="Ej: 45000" readonly>
            @error('precioventatecnico')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group col-md-4">
            <label for="inputPassword4">Precio Distribuidor</label>
            <input type="number" id="precioventadistribuidor" name="precioventadistribuidor" class="form-control @if($precioventadistribuidor == '') @else @error('precioventadistribuidor') is-invalid @else is-valid @enderror @endif" id="inputPassword4" wire:model.lazy="precioventadistribuidor" placeholder="Ej: 25000" readonly>
            @error('precioventadistribuidor')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="form-row" style="display: none">
          <div class="form-group col-md-4">
            <label for="inputEmail4">Unidad Medida</label>
            <select class="form-control @if($unidad_medida == '') @else @error('unidad_medida') is-invalid @else is-valid @enderror @endif" id="exampleFormControlSelect1" wire:model.lazy="unidad_medida" autocomplete="off">
              <option value="">Seleccione una opción</option>
              @foreach ($unidades as $unidad)
              <option value="{{ $unidad->id }}">{{ $unidad->name }}</option>
              @endforeach
            </select>
            @error('unidad_medida')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group col-md-4" x-data="{
            open: false,
            get isOpen() { return this.open },
            toggle() { this.open = !this.open },
         }">
            <label for="inputPassword4">Marca<i @popper(Crear marca) class="bi bi-plus-circle-fill ml-1 text-success" style="cursor: pointer" @click="toggle()"></i></label>
            <div x-show="isOpen">
              <input type="text" class="form-control @if($nuevamarca == '') @else @error('nuevamarca') is-invalid @else is-valid @enderror @endif mb-2" placeholder="Nueva marca" wire:keydown.enter="guardarMarca" wire:model.lazy="nuevamarca" autocomplete="off">
              @error('nuevamarca')
              <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
            <select class="form-control @if($marca == '') @else @error('marca') is-invalid @else is-valid @enderror @endif" id="exampleFormControlSelect1" wire:model.lazy="marca" autocomplete="off">
              @foreach ($marcas as $marca)
              <option value="{{ $marca->id }}">{{ $marca->name }}</option>
              @endforeach
            </select>
            @error('marca')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group col-md-4">
            <label for="inputPassword4">Fecha de vencimiento</label>
            <input type="date" class="form-control @if($fecha_vencimiento == '') @else @error('fecha_vencimiento') is-invalid @else is-valid @enderror @endif" id="inputPassword4" wire:model.lazy="fecha_vencimiento" autocomplete="off">
            @error('fecha_vencimiento')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

        </div>

        <div class="form-row">
          <div class="form-group col-md-8">
            <label for="inputEmail4">Imagen</label>
            <input type="file" class="form-control @if($image == '') @else @error('image') is-invalid @else is-valid @enderror @endif" id="image" name="image" wire:model.lazy="image">
            @error('image')
            <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>

     {{--      <div class="form-group col-md-4">
            <label for="inputEmail4">Estado </label>

            <select class="form-control @if($status == '') @else @error('status') is-invalid @else is-valid @enderror @endif" id="exampleFormControlSelect1" wire:model.lazy="status">
              <option value="ACTIVE">ACTIVO</option>
              <option value="DESACTIVE">DESACTIVADO</option>
            </select>
            @error('status')
            <span class="text-danger">{{ $message }}</span>
            @enderror

          </div>


      <div class="form-group col-md-4">
            <label for="inputEmail4">Precio venta actual </label>

            <input type="number" class="form-control" id="precio_venta_actual" name="precio_venta_actual" wire:model.lazy="precio_venta_actual">
            @error('status')
            <span class="text-danger">{{ $message }}</span>
            @enderror

          </div>--}}


        </div>


        @if($photo)
            <div class="mb-4"> <img style="height: 150px; width: 150px;" src="{!! Config::get('app.URL') !!}/storage/{{ $photo }}"
                alt="">
            </div>

        @elseif($image)
            <div class="mb-4"> <img style="height: 150px; width: 150px;" src="{{ $image->temporaryUrl() }}"
                    alt="">
            </div>

        @endif


        <button type="button" class="btn btn-success float-right ml-2" wire:click="storeOrupdate">Guardar</button>
        <x-adminlte-button class="float-right" wire:click="cancel" theme="danger" label="Cancelar" data-dismiss="modal" />

        <x-slot name="footerSlot" class="mt-0 mb-0 p-0">
          <small id="emailHelp" class="form-text text-muted">* Campo obligatorio</small>
        </x-slot>
      </div>
    </div>

  </div>
</div>

<script>

 window.addEventListener('alert', event => {

        Swal.fire(
            'Producto actualizado correctamente',
            '',
            'success'
        )
    })


</script>
        <script>
            window.addEventListener('close-modal', event => {
                     //alert('Hola mundo');
                        $('#productomodal').hide();
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                    })
        </script>
