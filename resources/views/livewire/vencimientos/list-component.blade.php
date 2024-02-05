<div>
    @section('title', 'Presentaciones')

    @section('content_header')

        <div class="row float-right mr-1">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Parametros</li>

                    <li class="breadcrumb-item active" aria-current="page">Presentaciones</li>
                </ol>
            </nav>
        </div>

        <br>
        @stop
    <div> @include('includes.alert')</div>
    @include('popper::assets')
    <div class="card card-info  mt-2">
        <div class="card-header">

            <div class="row">
                <div class="col-sm-10">
                    <h3>Presentaciones</h3>
                </div>


                <div class="col-sm-2 float-right bg-secon">
                    <div class="form-check form-check-inline bg-danger" w="20px" style="width: 20px; height: 20px; border-radius:5px" >
                        <input class="form-check-input mt-1 mb-1" type="checkbox" id="inlineCheckbox1" value="option1">

                      </div>
                      <div class="form-check form-check-inline bg-warning text-center"  style="width: 20px; height: 20px; border-radius:5px">
                        <input class="form-check-input mt-1 " type="checkbox" id="inlineCheckbox2" value="option2">

                      </div>
                      <div class="form-check form-check-inline bg-success " style="width: 20px; height: 20px; border-radius:5px">
                        <input class="form-check-input mt-1" type="checkbox" id="inlineCheckbox3" value="option3" >

                      </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Lote</th>
                        <th>Cantidad</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vencimientos as $ven)
                    <tr>
                        <td>{{ $ven->product->code }} </td>
                        <td>{{ ucwords($ven->product->name ) }}</td>
                        <td>{{ $ven->lote }}</td>
                        <td>{{ ($ven->cantidad_ingresada - $ven->cantidad_vendida) }}  </td>
                        <td>{{$ven->fecha_vencimiento}}</td>
                        <td>@if($ven->status == 'ACTIVE')
                            <span class="badge bg-success">ACTIVO</span>
                        @else
                        <span class="badge bg-danger">DESHABILITADO</span>
                        @endif</td>
                        <td class="text-center">


                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="2">
                            <p>No se encontraron registros...</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <nav aria-label="...">
                <ul class="pagination">
                    {{ $vencimientos->links() }}
                </ul>
            </nav>

        </div>
    </div>
</div>
@include('modals.presentacion.createpresentacion')
<script>
      window.addEventListener('alert', () => {
            Swal.fire({
                icon: "success",
                title: "Registro actualizado correctamente",
                showConfirmButton: false,
                timer: 1500
            });


        });
</script>
