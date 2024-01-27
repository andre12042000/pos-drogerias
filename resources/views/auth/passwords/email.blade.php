@extends('layouts.app')

@section('content')
    <div class=" py-5">
        <div class=" py-5">
            <div class="card">

                <div class="card-header bg-info">


                    <strong class="mb-5 "> Restablecer Contraseña</strong>
                </div>

                <div class="card-body ">
                    @if (session('status'))
                        <div class="alert alert-info" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email"
                                class="col-md-5  col-form-label  text-dark">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-7">
                                <input id="email" placeholder="Ingrese correo de recuperación" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mt-5">
                            <div class="col-md-12 ">


                                <button type="submit" class="btn btn-info mr-2 float-right">
                                    Enviar enlace
                                </button>

                                <a class=" btn btn-outline-info float-right  mr-2 " href="{{ route('login') }}">Iniciar
                                    Sesion</a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
