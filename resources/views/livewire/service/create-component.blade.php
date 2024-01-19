<div>
    @include('popper::assets')
    <div class="card">
        <div class="card-header">
            <h1>Registrar nueva orden de servicio</h1>
        </div>
        <div class="card-body">

            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cliente"
                                aria-label="Recipient's username with two button addons"
                                aria-describedby="button-addon4" value="{{ $client_name }}">
                            <div class="input-group-append" id="button-addon4">
                                <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"
                                        style="cursor: pointer" data-toggle="modal"
                                        data-target="#searchclient"></i></button>
                                <button class="btn btn-outline-secondary" type="button"><i
                                        class="bi bi-plus-circle-fill" style="cursor: pointer" data-toggle="modal"
                                        data-target="#modalCustom"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Equipo o vehiculo"
                                aria-label="Recipient's username with two button addons"
                                aria-describedby="button-addon4" value="">
                            <div class="input-group-append" id="button-addon4">
                                <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"
                                        style="cursor: pointer" data-toggle="modal"
                                        data-target="#searchvehiculo"></i></button>
                                <button class="btn btn-outline-secondary" type="button"><i
                                        class="bi bi-plus-circle-fill" style="cursor: pointer" data-toggle="modal"
                                        data-target="#searchvehiculo"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <select class="form-control" id="exampleFormControlSelect1">
                                <option value="">Profesional asignado</option>
                                @foreach ($users as $user)
                                    <option>{{ ucwords($user->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <x-adminlte-textarea name="taMsg" rows=5 label-class="text-primary"
                            placeholder="Motivo del mantenimiento..." disable-feedback>
                        </x-adminlte-textarea>
                    </div>
                </div>
                <br>
                <div class="card-footer">
                    <x-adminlte-button label="Guardar" theme="primary" icon="fas fa-check" class="float-right"
                        wire:click="save" />

                </div>
            </div>
        </div>






    </div>
