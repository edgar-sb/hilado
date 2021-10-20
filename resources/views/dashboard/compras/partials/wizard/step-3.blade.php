<div class="row">
    <div class="col-12">
        <div class="form-group m-form__group">
            <label for="email" class="col-form-label">Correo electrónico del proveedor:</label>
            {{ Form::text('email', null, ['class' => 'form-control', 'id' => 'email']) }}
            <span class="m-form__help" style="color: gray">
                NOTA: Este correo electrónico será al que se enviarán todas las notificaciones.
            </span>
        </div>
        <div class="form-group row">
            <label for="maniobras" class="col-12 col-sm-3 col-md-4 col-form-label">Maniobras: </label>
            <div class="col-12 col-sm-9 col-md-8">
                {{ Form::number('maniobras', null, ['class' => 'form-control', 'id' => 'maniobras']) }}
            </div>
        </div>
        <div class="form-group row">
            <label for="estadia" class="col-12 col-sm-3 col-md-4 col-form-label">Estadía: </label>
            <div class="col-12 col-sm-9 col-md-8">
                {{ Form::number('estadia', null, ['class' => 'form-control', 'id' => 'estadia']) }}
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12 text-right">
                <button type="button" class="btn btn-primary" onclick="storeOrdenCompra()" id="btn-store-orden-compra">Guardar compra</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function storeOrdenCompra() {
            mApp.block("#smartwizard", {});
            $.ajax({
                method: "POST",
                url: "{{ route('compras.store') }}",
                data: {
                    cve_doc: $('#btn-store-orden-compra').data('cve_doc'),
                    email: $('#email').val(),
                    maniobras: $('#maniobras').val(),
                    estadia: $('#estadia').val()
                }
            })
                .done(function( response ) {
                    if(response.success) {
                        swal({
                            title: "¡Bien hecho!",
                            text: response.success,
                            type: 'success',
                            confirmButtonText: 'Entendido'
                        }).then(function () {
                            window.location = "{{ route('compras.index') }}"
                        });
                    } else {
                        swal({
                            title: "¡Error!",
                            text: response.error,
                            type: 'error',
                            confirmButtonText: 'Entendido'
                        });
                    }
                    mApp.unblock("#smartwizard", {});
                })
                .fail(function( response ) {
                    showErrors(Object.values(response.responseJSON.errors));
                    mApp.unblock("#smartwizard", {});
                });
        }
    </script>
@endpush
