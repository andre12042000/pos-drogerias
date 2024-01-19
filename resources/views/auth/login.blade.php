@extends('layouts.app')

@section('content')
<div class="container-xl float-center" style="background-color: rgb(11,33,47,.5); border-radius: 5px;">


    <div class="main-div">
        <div class="panel">
            <h2 class="text-white">INICIAR SESIÓN</h2>
            <p class="text-white"> Ingrese su usuario y contraseña</p>
        </div>

        <form method="POST" action="{{ route('login') }}" id="Login">
            @csrf

            <div class="form-group">
                    <input type="email" class="form-control" id="email" required placeholder="Correo electrónico" name="email" value="{{ old('email') }}">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
            </div>

            <div class="form-group">

                <input type="password" class="form-control" id="password" required placeholder="Contraseña" name="password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

            </div>

            <div class="form-check mt-4 mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember')
                    ? 'checked' : '' }}>

                <label class="form-check-label text-white" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>


            <button type="submit" class="btn btn-primary" > <strong>INICIAR SESIÓN</strong> </button>


                <a class="link-primary fs-7 fw-bolder" href="{{ route('password.request') }}">

                </a>


            @if (Route::has('password.request'))
                <div class="forgot text-center mt-4">
                    <a href="{{ route('password.request') }}" class="text-white"> ¿Olvidaste la contraseña?</a>
                </div>
             @endif


        </form>
    </div>
</div>
@endsection
