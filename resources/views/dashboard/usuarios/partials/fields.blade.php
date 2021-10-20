{!! Form::hidden('user_id', optional($user)->id) !!}
<div class="form-group row">
  {!!Form::label('nombre', 'Nombre: ', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label', ''])!!}
    <div class="col-12 col-sm-9 col-md-8">
      {!!Form::text('nombre', optional($user)->nombre, ['class' => 'form-control', 'required'])!!}
    </div>
</div>
<div class="form-group row">
    {!!Form::label('email', 'Email:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label'])!!}
    <div class="col-12 col-sm-9 col-md-8">
      {!!Form::email('email', optional($user)->email, ['class' => 'form-control', 'required'])!!}
    </div>
</div>
@if($rol == 'administrador')
  <div class="form-group row">
      {!!Form::label('password', 'Contraseña:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label'])!!}
      <div class="col-12 col-sm-9 col-md-8">
        @if($action = 'edit')
          {!!Form::password('password',['class' => 'form-control'])!!}
        @else
          {!!Form::password('password',['class' => 'form-control', 'required'])!!}
        @endif
      </div>
  </div>
  <div class="form-group row">
      {!!Form::label('password_confirmation', 'Repite la contraseña:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label'])!!}
      <div class="col-12 col-sm-9 col-md-8">
        @if($action = 'edit')
          {!!Form::password('password_confirmation',['class' => 'form-control'])!!}
        @else
          {!!Form::password('password_confirmation',['class' => 'form-control', 'required'])!!}
        @endif
      </div>
  </div>
@endif
{{ Form::hidden('rol', $rol) }}
<div class="form-group row">
    <div class="col-12  text-right">
        <a href="{{ url()->previous() }}" class="btn btn-default">Regresar</a>
        <button type="submit" class="btn btn-success ml-3">Guardar</button>
    </div>
</div>
