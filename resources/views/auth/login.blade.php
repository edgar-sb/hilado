@extends('layouts.auth')

@section('content')

    <div class="row">
        <div class="col-12 col-sm-6 offset-sm-3">
            <img src="{{ asset('images/logo.png') }}" alt="" class="img-fluid">
        </div>
    </div>

    <div class="m-login__signin">
        <form class="m-login__form m-form" role="form" action="{{ route('login') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group m-form__group">
                <input class="form-control m-input @error('email') is-invalid @enderror @if(session('error')) is-invalid @endif" type="text"  placeholder="Correo elecrónico" name="email" autocomplete="off">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                @if(session('error'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ session('error') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group m-form__group">
                <input class="form-control m-input @error('password') is-invalid @enderror" type="password" placeholder="Contraseña" name="password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="row m-login__form-sub">
                <div class="col m--align-left">
                    <a href="{{ route('password.request') }}" class="m-link--primary">Olvidé mi contraseña</a>
                </div>
            </div>

            <div class="m-login__form-action">
                <button type="submit" class="btn btn-primary m-btn m-btn--pill m-btn--custom m-btn--air">Iniciar sesión</button>
            </div>

        </form>
    </div>
@endsection
