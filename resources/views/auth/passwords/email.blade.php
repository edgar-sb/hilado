@extends('layouts.auth')

@section('content')

    <div class="row">
        <div class="col-12 col-sm-6 offset-sm-3">
            <img src="{{ asset('images/logo.png') }}" alt="" class="img-fluid">
        </div>
    </div>

    @if (session('status'))
        <br><br>
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="m-login__signin">
        <form class="m-login__form m-form" role="form" action="{{ route('password.email') }}" method="POST">
           @csrf
            <div class="form-group m-form__group">
                <input id="email" type="email" placeholder="Correo electrónico" class="form-control @error('email') is-invalid @enderror m-input" name="email" value="{{ old('email') }}" required autocomplete="off">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
            </div>
            <div class="row m-login__form-sub">
                <div class="col m--align-left">
                    <a href="{{ route('login') }}" class="m-link--primary">Iniciar sesión</a>
                </div>
            </div>
            <div class="m-login__form-action">
                <button type="submit" class="btn btn-primary m-btn m-btn--pill m-btn--custom m-btn--air">Enviar correo electronico</button>
            </div>
        </form>
    </div>
@endsection
