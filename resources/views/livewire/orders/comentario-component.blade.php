<div>
   <div class="card">
        <div class="row m-4">

            <div class="form-floating mb-3">
                <input type="text" class="form-control " id="floatingInput" placeholder="name@example.com" @if ($status == 2 ) disabled @endif wire:model.lazy = 'comentario' wire:keydown.enter="save">
                <label for="floatingInput">&nbsp;&nbsp;Observación</label>
              </div>
            @error('comentario')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
        </div>
        <div class="row m-4">
            @include('includes.alert')

            <div class="list-group">

                @foreach ($comentarios as $comentario)

                <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                      <p class="mb-1">{{ ucwords($comentario->comentario) }}</p>
                      <small class="text-muted">{{ \Carbon\Carbon::parse($comentario->created_at)->diffForHumans() }}</small>
                    </div>
                    <small class="text-muted">{{ ucwords($comentario->user->name ) }}</small>
                  </a>

                @endforeach

              </div>
        </div>

   </div>
</div>
