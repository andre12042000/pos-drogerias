<div>
    <form>
        <div class="form-row">
            <div class="form-group row col-lg-6">
                <label for="password" class="col-md-3 col-form-label text-md-right">Nueva Contraseña</label>
                <div class="col-md-9 input-group">
                    <input type="password"
                        class="form-control @if ($errors->has('password')) is-invalid @elseif($password != '') is-valid @endif"
                        placeholder="Nueva contraseña" wire:model.lazy="password" id="password">

                    <div class="input-group-append" style="height: 38px">
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePasswordVisibility('password')">
                            <i class="ion-eye-disabled" aria-hidden="true"></i>
                        </button>
                    </div>
@if ($errors->has('password'))
                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row col-lg-6">
                <label for="verify_password" class="col-md-3 col-form-label text-md-right">Confirmar </label>
                <div class="col-md-9 input-group">
                    <input type="password"
                        class="form-control @if ($errors->has('verify_password')) is-invalid @elseif($verify_password != '') is-valid @endif"
                        placeholder="Verificar contraseña" wire:model.lazy="verify_password" id="verify_password">

                    <div class="input-group-append" style="height: 38px">
                        <button type="button" class="btn btn-outline-secondary "
                            onclick="togglePasswordVisibility('verify_password')">
                            <i class="ion-eye-disabled" aria-hidden="true"></i>
                        </button>
                    </div>
                    @if ($errors->has('verify_password'))
                        <div class="invalid-feedback">{{ $errors->first('verify_password') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="">
            <button type="button" class="btn btn-secondary mt-2 float-right" wire:click="save">Cambiar contraseña</button>
        </div>
    </form>

    <script>
        function togglePasswordVisibility(fieldId) {
            var passwordField = document.getElementById(fieldId);
            var fieldType = passwordField.type;

            // Cambia el tipo de campo de contraseña a texto o viceversa
            passwordField.type = (fieldType === 'password') ? 'text' : 'password';
        }
    </script>
    <style>
        .button-container {
    display: inline-block;
}

.invalid-feedback {
    margin-top: 4px; /* Ajusta el espacio entre el botón y el mensaje de error según sea necesario */
}
    </style>
</div>
