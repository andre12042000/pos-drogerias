<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-secondary">
            <h5 class="modal-title " id="staticBackdropLabel"><strong>Nuevo equipo o vehiculo</strong> </h5>
            <button type="button" class="btn-close" data-dismiss="modal" wire:click="cancel" aria-label="Close"></button>

        </div>
        <div class="modal-body ">
            @if(session()->has('message'))

            <div class="alert alert-success alert-dismissible fade show t:0.2 mt-2 " role="alert">
                <i class="bi bi-check-circle-fill"></i> {{session('message')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>


        @endif
            <div>
                <div class="container ">

                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-floating mb-3">
                                <input type="text"
                                    class="form-control @error('referencia') is-invalid @elseif($referencia != '') is-valid @enderror"
                                    id="floatingInput" placeholder="name@example.com" wire:model="referencia">
                                <label for="floatingInput">Referencia</label>
                                @error('referencia')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput"
                                    placeholder="name@example.com" wire:model="color">
                                <label for="floatingInput">Color</label>
                            </div>

                            <div class="form-group " x-data="{
                                open: false,
                                get isOpen() { return this.open },
                                toggle() { this.open = !this.open },
                            }">
                                <label for="inputPassword4">Marca<i title="Crear una nueva marca"
                                        class="bi bi-plus-circle-fill ml-1 text-success" style="cursor: pointer"
                                        @click="toggle()"></i></label>
                                <div x-show="isOpen">
                                    <input type="text"
                                        class="form-control @if ($nuevamarca == '') @else @error('nuevamarca') is-invalid @else is-valid @enderror @endif mb-2"
                                        placeholder="Nueva marca" wire:keydown.enter="guardarMarca"
                                        wire:model.lazy="nuevamarca" autocomplete="off">
                                    @error('nuevamarca')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <select
                                    class="form-select @if ($marca == '') @else @error('marca') is-invalid @else is-valid @enderror @endif"
                                    id="exampleFormControlSelect1" wire:model.lazy="marca" autocomplete="off">
                                    <option value="">Selecciona una marca</option>
                                    @foreach ($brand as $marca)
                                        <option value="{{ $marca->id }}">{{ $marca->name }}</option>
                                    @endforeach
                                </select>
                                @error('marca')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>

                        <div class="col-sm-6">
                            <div class="form-floating mb-3 ">
                                <input type="text"
                                    class="form-control @error('placa') is-invalid @elseif($placa != '') is-valid @enderror"
                                    id="floatingInput" placeholder="name@example.com" wire:model="placa">
                                <label for="floatingInput">Placa - Serial</label>
                                @error('placa')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput"
                                    placeholder="name@example.com" wire:model="modelo">
                                <label for="floatingInput">Modelo</label>
                            </div>



                            <div class="form-group " x-data="{
                                open: false,
                                get isOpen() { return this.open },
                                toggle() { this.open = !this.open },
                            }">
                                <label for="inputPassword4">Tipo Equipo<i title="Crear un nuevo tipo de equipo"
                                        class="bi bi-plus-circle-fill ml-1 text-success" style="cursor: pointer"
                                        @click="toggle()"></i></label>
                                <div x-show="isOpen">
                                    <input type="text"
                                        class="form-control @if ($nuevotipo == '') @else @error('nuevotipo') is-invalid @else is-valid @enderror @endif mb-2"
                                        placeholder="Nueva tipo" wire:keydown.enter="guardarTipos"
                                        wire:model.lazy="nuevotipo" autocomplete="off">
                                    @error('nuevotipo')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <select
                                    class="form-select @if ($tipoequipo == '') @else @error('tipoequipo') is-invalid @else is-valid @enderror @endif"
                                    id="exampleFormControlSelect1" wire:model.lazy="tipoequipo" autocomplete="off">
                                    <option value="">Selecciona un tipo</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->descripcion }}</option>
                                    @endforeach
                                </select>
                                @error('tipoequipo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" wire:click="cancel">Cancelar</button>
            <button type="button" class="btn btn-success" wire:click="storeOrupdate">Guardar</button>
        </div>
    </div>
</div>
