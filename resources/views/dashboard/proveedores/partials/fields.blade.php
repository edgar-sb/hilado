{!! Form::hidden('user_id', optional($proveedor)->user_id) !!}
<div class="form-group row">
    {{ Form::label('nombre', 'Nombre: ', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label']) }}
    <div class="col-12 col-sm-9 col-md-8">
        {{ Form::text('nombre', null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group row">
    {{ Form::label('razon_social', 'Razón social:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label']) }}
    <div class="col-12 col-sm-9 col-md-8">
        {{ Form::text('razon_social', null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group row">
    {{ Form::label('rfc', 'RFC:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label']) }}
    <div class="col-12 col-sm-9 col-md-8">
        {{ Form::text('rfc', null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group row">
    {{ Form::label('email', 'Email:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label']) }}
    <div class="col-12 col-sm-9 col-md-8">
        {{ Form::email('email', null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group row">
    {{ Form::label('dias_credito', 'Días de crédito:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label']) }}
    <div class="col-12 col-sm-9 col-md-8">
        {{ Form::number('dias_credito', null, ['class' => 'form-control', 'step' => '1', 'min' => '0', 'max' => '2147483647', 'placeholder' => 'No establecido']) }}
    </div>
</div>
<div class="form-group row">
    {{ Form::label('dias_vencer_factura', 'Días de vencimiento del acuse:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label']) }}
    <div class="col-12 col-sm-9 col-md-8">
        {{ Form::number('dias_vencer_factura', null, ['class' => 'form-control', 'step' => '1', 'min' => '0', 'max' => '2147483647', 'placeholder' => 'No establecido']) }}
    </div>
</div>
<div class="form-group row">
    {{ Form::label('dias_vencer_pago', 'Días de vencimiento del complemento de pago:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label']) }}
    <div class="col-12 col-sm-9 col-md-8">
        {{ Form::number('dias_vencer_pago', null, ['class' => 'form-control', 'step' => '1', 'min' => '0', 'max' => '2147483647', 'placeholder' => 'No establecido']) }}
    </div>
</div>
<div class="form-group m-form__group row">
    <label class="col-12 col-sm-3 col-md-4 col-form-label">Bloqueado:</label>
    <div class="col-12 col-sm-9 col-md-8">
        <span class="m-switch m-switch--outline m-switch--icon m-switch--success" style ="vertical-align: -50px;" >
            <label>
                <input type="checkbox" name="bloqueado" {{ optional($proveedor)->bloqueado? 'checked' : '' }}>
                <span></span>
            </label>
        </span>
    </div>
</div>
<div class="form-group row">
    <div class="col-12">
        <h5>Emails adicionales
            <span data-toggle="m-tooltip" class="help-icon" data-direction="left" data-width="auto" title="A los emails adicionales se les enviará copia de todas las notificaciones del proveedor">
                <i class="flaticon-info m--icon-font-size-sm1"></i>
            </span>
        </h5>
    </div>
</div>
<div id="emails">
    @isset($proveedor->emails)
        @foreach($proveedor->emails as $email)
            <div class="form-group row">
                {{ Form::label('emails[]', 'Email adicional:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label  mb-2']) }}
                <div class="col-12 col-sm-7 col-md-6">
                    {{ Form::text('emails[]', $email, ['class' => 'form-control mb-2', 'required']) }}
                </div>
                <div class="col-12 col-sm-2 text-right">
                    <button class="btn btn-danger btn-remover-email mb-2" type="button"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        @endforeach
    @endisset
</div>
<div class="form-group row">
    <div class="col-12">
        <hr>
    </div>
    <div class="col-12 text-right">
        <button type="button" class="btn btn-info btn-sm" id="btn-agregar-email">Agregar email adicional</button>
    </div>
</div>
<div class="form-group row">
    <div class="col-12">
        <h5>Dirección fiscal</h5>
    </div>
</div>
<div class="form-group row">
    {{ Form::label('calle', 'Calle:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label']) }}
    <div class="col-12 col-sm-9 col-md-8">
        {{ Form::text('calle', null, ['class' => 'form-control']) }}
    </div>
</div>
<form-ubicacion :selectestados="$select_estados" :proveedor="$proveedor"></form-ubicacion>

<div class="form-group row">
    <div class="col-12">
        <h5>Contacto</h5>
    </div>
</div>
<div class="row">
    <div class="form-group  m-form__group col-12" id="m_repeater_1">
        <div data-repeater-item class="form-group m-form__group row">
            <div class="col-12">
                <div class="form-group row">
                    {{ Form::label('contacto_nombre', 'Nombre:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label']) }}
                    <div class="col-12 col-sm-9 col-md-8">
                        {{ Form::text('contacto_nombre', null, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group row">
                    {{ Form::label('contacto_puesto', 'Puesto:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label']) }}
                    <div class="col-12 col-sm-9 col-md-8">
                        {{ Form::text('contacto_puesto', null, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group row">
                    {{ Form::label('contacto_email', 'Email:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label']) }}
                    <div class="col-12 col-sm-9 col-md-8">
                        {{ Form::email('contacto_email', null, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group row">
                    {{ Form::label('contacto_telefono', 'Teléfono:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label']) }}
                    <div class="col-12 col-sm-9 col-md-8">
                        {{ Form::text('contacto_telefono', null, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group row">
                    {{ Form::label('comentarios', 'Comentarios adicionales:', ['class' => 'col-12 col-sm-3 col-md-4 col-form-label']) }}
                    <div class="col-12 col-sm-9 col-md-8">
                        {{ Form::textarea('comentarios', null, ['class' => 'form-control', 'id' => 'comentarios','rows' => 5]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-12  text-right">
        <a href="{{ url()->previous() }}" class="btn btn-default">Regresar</a>
        <button type="submit" class="btn btn-success ml-3">Guardar</button>
    </div>
</div>

@push('scripts')
    <script>
        let html_email_adicional = `<div class="form-group row">
        <label for="emails[]" class="col-12 col-sm-3 col-md-4 col-form-label  mb-2">Email adicional:</label>
        <div class="col-12 col-sm-7 col-md-6">
            <input class="form-control mb-2" required="" name="emails[]" type="text" id="emails[]">
        </div>
        <div class="col-12 col-sm-2 text-right">
            <button class="btn btn-danger btn-remover-email mb-2" type="button"><i class="fas fa-trash"></i></button>
        </div>
    </div>`;
        let emails = $('#emails')

        $(document).on("click", "#btn-agregar-email", function() {
            emails.append(html_email_adicional);
        });
        $(document).on("click", ".btn-remover-email", function() {
            $(this).parent().parent().remove();
        });
    </script>
@endpush

