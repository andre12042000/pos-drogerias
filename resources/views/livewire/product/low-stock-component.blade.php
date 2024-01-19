<div>
    <div> @include('includes.alert')</div>
    @include('popper::assets')
    <div class="card card-info mt-2">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-10">
                    <h3>Productos</h3>
                </div>
                <div class="col-sm-2">
                    <div class="input-group float-right">

                        <select wire:model="cantidad_registros" class="form-select col-sm-12 mr-2"
                        aria-label="Default select example">
                        <option value="10"> 10</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="300">300</option>
                    </select>


                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="tabProducts">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th class="text-center">Cant. faltante</th>
                        <th class="text-center">Stock mínimo</th>
                        <th class="text-center">Stock actual</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productos as $product)
                    <tr>
                        <td>{{ $product['code'] }}</td>
                        <td> {{ $product['name'] }} </a></td>
                        <td class="text-center"><span class="badge bg-dark">{{$product['recomendado']}}</span></td>
                        <td class="text-center"> {{ $product['stock_min'] }} </a></td>
                        <td class="text-center">@if( $product['stock'] == $product['stock_min']) <span class="badge bg-warning">{{$product['stock']}}</span> @else  <span class="badge bg-danger">{{$product['stock']}}  @endif</td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="10">
                            <p>No se encontraron registros...</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer ">
            <nav class="" aria-label="">
                <ul class="pagination">

                </ul>
            </nav>

        </div>
    </div>
</div>
