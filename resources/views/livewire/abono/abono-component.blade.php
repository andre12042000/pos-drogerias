<div>
    <div x-data>
        @include('popper::assets')
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title " id="staticBackdropLabel"> <strong>Gestión de
                        saldos</strong> </h5>

                </div>
                <div class="modal-body">
                    <div>@include('includes.alert')</div>

                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-5 col-form-label text-end text-end">Tipo</label>
                        <div class="col-sm-7">
                            <input type="text" wire:model.lazy="tipo" id="tipo" name="tipo"
                                class="form-control"
                                id="exampleFormControlInput1" placeholder="Ejemplo: Orden de trabajo" disabled readonly>

                            @error('tipo')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-5 col-form-label text-end">Codigo</label>
                        <div class="col-sm-7">
                            <input type="text" wire:model.lazy="codigo" id="codigo" name="codigo"
                                class="form-control"
                                id="exampleFormControlInput1" placeholder="Código de referencia" disabled readonly>

                            @error('codigo')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-5 col-form-label text-end">Total</label>
                        <div class="col-sm-7">
                            <input type="text" wire:model.lazy="total" id="total" name="total"
                                class="form-control"
                                id="exampleFormControlInput1" placeholder="Valor total del trabajo" disabled readonly>

                            @error('total')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-5 col-form-label text-end">Abonado</label>
                        <div class="col-sm-7">
                            <input type="text" wire:model.lazy="abonado" id="total" name="abonado"
                                class="form-control"
                                id="exampleFormControlInput1" placeholder="Total abonado" disabled readonly>

                            @error('total')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-5 col-form-label text-end">Saldo</label>
                        <div class="col-sm-7">
                            <input type="text" wire:model.lazy="saldo" id="saldo" name="saldo"
                                class="form-control"
                                id="exampleFormControlInput1" placeholder="Ejemplo: Código de referencia" disabled readonly>

                            @error('total')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                <hr>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5 col-form-label text-end">Abonar <span
                        class="fs-6 text-danger ml-2" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Campo obligatorio">*</span></label>
                    <div class="col-sm-7">
                        <input type="number" wire:model.lazy="abonar" id="abonar" name="abonar"
                            class="form-control"
                            id="exampleFormControlInput1" placeholder="Valor que abonan" autofocus>
                        @error('abonar')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5 col-form-label text-end">Método de pago <span
                        class="fs-6 text-danger ml-2" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Campo obligatorio">*</span></label>
                    <div class="col-sm-7">
                        @include('includes.metodospago')

                        @error('metodo_pago')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="form-group row">
                    <label for="staticEmail" class="col-sm-5 col-form-label text-end">Nuevo saldo</label>
                    <div class="col-sm-7">
                        <input type="text" wire:model="nuevo_saldo" id="total" name="abonado"
                            class="form-control"
                            id="exampleFormControlInput1" placeholder="Saldo despues de abonar" disabled readonly>

                        @error('nuevo_saldo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <button type="button" class="btn btn-outline-success float-right ml-2"
                                wire:click="save">Guardar</button>
                <x-adminlte-button class="float-right" wire:click="cleanData" theme="danger" label="Cancelar"
                                data-dismiss="modal" />
                </div>
            </div>
        </div>
    </div>



    @section('css')

        <style>
            /* Estilo para la tabla */
            .table {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
                border-collapse: collapse;
            }

            /* Estilo para las celdas del encabezado */
            .table th,
            .table td {
                padding: 0.75rem;
                vertical-align: top;
                border-top: 1px solid #dee2e6;
            }

            /* Estilo para las filas impares */
            .table tbody tr:nth-child(odd) {
                background-color: #f8f9fa;
            }

            /* Estilo para el mensaje cuando no hay registros */
            .table tbody tr td[colspan="6"] {
                text-align: center;
                padding: 10px;
            }
        </style>

    @stop


    {{-- <script>
        window.addEventListener('alert', event => {

            Swal.fire(
                'Abono aplicado correctamente',
                '',
                'success'
            )
        })


    </script>

    <script>
        window.addEventListener('close-modal', event => {
                 //alert('Hola mundo');
                    $('#abonomodal').hide();
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                })
    </script> --}}

</div>
