<div>
    <div class="container">
        <div class="row">

            @foreach ($users as $user)
            <div class="col-sm">
                <div class="card">
                    <div class="card-header">
                        {{ ucwords($user->name) }}
                    </div>

                    @isset($user->orderservices)
                    <ul class="list-group">
                        @foreach ($user->orderservices as $order)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                           {{ $order->equipo->brand->name }} {{ $order->equipo->placa }}
                            <span class="badge badge-primary badge-pill">14</span>
                          </li>
                        @endforeach
                    </ul>
                    @endisset

                </div>
            </div>
            @endforeach


        </div>
      </div>




</div>
