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
       <br><br>
       <form method="POST" action="{{ route('password.update') }}">
       @csrf

       <input type="hidden" name="token" value="{{ $token }}">

           <div class="form-group m-form__group">
               <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>
                   @error('email')
                   <span class="invalid-feedback" role="alert">
                       <strong>{{ $message }}</strong>
                   </span>
               @enderror
           </div>
           <br>
           <div class="form-group m-form__group">
               <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Nueva contraseña" required autocomplete="new-password">
               @error('password')
                   <span class="invalid-feedback" role="alert">
                       <strong>{{ $message }}</strong>
                   </span>
               @enderror
           </div>
           <br>
           <div class="form-group m-form__group">
               <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Nueva contraseña" required autocomplete="new-password">
               @error('password')
                   <span class="invalid-feedback" role="alert">
                       <strong>{{ $message }}</strong>
                   </span>
               @enderror
           </div>
           <br>
           <div class="m-login__form-action text-center">
               <button type="submit" class="btn btn-primary m-btn m-btn--pill m-btn--custom m-btn--air">Guardar contraseña</button>
           </div>
       </form>
   </div>
@endsection
