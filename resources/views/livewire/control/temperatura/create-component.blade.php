<div>
    <div class="modal-dialog modal-xl" theme="primary">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de
                        sitios para control de temperatura</strong> </h5>
                <button type="button" class="btn-close" data-dismiss="modal" wire:click="cleanData"
                    aria-label="Close"></button>
            </div>
            @include('popper::assets')
            <div class="modal-body">
                <div>@include('includes.alert')</div>


                <hr>
                @forelse ($sitios as $index => $sitio)
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Código que se ejecutará al cargar el formulario
                                    updateSitioId({{ $loop->index }}, {{ $sitio->id }});
                                });

                                function updateSitioId(index, id) {
                                    // Tu lógica para actualizar el sitio_id
                                    Livewire.emit('updateSitioId', index, id);
                                }
                            </script>

                            <input type="hidden" class="form-control" id="sitio_id" value="{{ $sitio->id }}">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="sitio"
                                    placeholder="name@example.com" value="{{ $sitio->name }}" disabled readonly>
                                <label for="floatingInput">Sitio</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="temperatura" placeholder="name@example.com" wire:model.defer="sitiosData.{{ $loop->index }}.temperatura"  min="0" max="99">
                                <label for="floatingInput">Temperatura</label>
                            </div>
                            @error("sitiosData.{$loop->index}.temperatura") <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="humedad" placeholder="name@example.com" wire:model.defer="sitiosData.{{ $loop->index }}.humedad">
                                <label for="humedad">Humedad</label>
                            </div>
                            @error("sitiosData.{$loop->index}.humedad") <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="cadena_frio" placeholder="name@example.com" wire:model.defer="sitiosData.{{ $loop->index }}.cadena_frio">
                                <label for="humedad">Humedad</label>
                            </div>
                            @error("sitiosData.{$loop->index}.cadena_frio") <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            @empty

                    <div class="container">
                        <div class="row">
                            <p> Necesitas configurar al menos un sitio para empezar el control.</p>
                            <div>
                            </div>
                @endforelse

                <button type="button" class="btn btn-success float-right ml-2" wire:click="save">Guardar</button>
                <x-adminlte-button class="float-right" wire:click="cleanData" theme="danger" label="Cancelar"
                    data-dismiss="modal" />
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('close-modal', event => {
             //alert('Hola mundo');
                $('#registrarSeguimientoTemperatura').hide();
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            })
</script>

<script>
    window.addEventListener('registro-creado', () => {
          Swal.fire({
              icon: "success",
              title: "Registro actualizado correctamente",
              showConfirmButton: false,
              timer: 1500
          });

          setTimeout(() => {
            location.reload();
        }, 1500);


      });
</script>


