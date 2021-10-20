<div class="form-group row">
    <label for="estado" class="col-12 col-sm-3 col-md-4 col-form-label">Estado: </label>
    <div class="col-12 col-sm-5 col-md-4">
        {{ Form::select('estado_id', $selectestados, 0, ['class' => 'form-control m-select2', 'placeholder' => '']) }}
    </div>
    <div class="col-12 col-sm-4 col-md-4">
        {{ Form::text('estado_nombre', optional($proveedor)->estado_nombre, ['class' => 'form-control', 'readonly', 'disabled', 'placeholder' => 'Selecciona un estado']) }}
    </div>
</div>
<div class="form-group row">
    <label for="municipio" class="col-12 col-sm-3 col-md-4 col-form-label">Municipio: </label>
    <div class="col-12 col-sm-5 col-md-4" id="select-municipios">
        {{ Form::select('municipio_id', [], null, ['class' => 'form-control m-select2', 'placeholder' => '']) }}
    </div>
    <div class="col-12 col-sm-4 col-md-4">
        {{ Form::text('municipio_nombre', optional($proveedor)->municipio_nombre, ['class' => 'form-control', 'readonly', 'disabled', 'placeholder' => 'Selecciona un municipio']) }}
    </div>
</div>
<div class="form-group row">
    <label for="colonia" class="col-12 col-sm-3 col-md-4 col-form-label">Colonia: </label>
    <div class="col-12 col-sm-5 col-md-4" id="select-colonias">
        {{ Form::select('colonia_id', [], null, ['class' => 'form-control m-select2', 'placeholder' => '']) }}
    </div>
    <div class="col-12 col-sm-4 col-md-4">
        {{ Form::text('colonia_nombre', optional($proveedor)->colonia_nombre, ['class' => 'form-control', 'readonly', 'disabled', 'placeholder' => 'Selecciona una colonia']) }}
    </div>
</div>

@push('scripts')
    <script>
        $(document).on('change', 'select[name=estado_id]', function () {
            let $select = $(this);
            let estado_id = $('select[name=estado_id]').val();
            let $form = $("#select-municipios");
            let url = "{{ route('proveedor.load.ubicacion', [
                    'estado_id' => 'ESTADOID',
                ]) }}".replace(/&amp;/g, '&');
            url = url.replace('ESTADOID', estado_id);

            mApp.block("#select-municipios", {});

            $form.load(url, function(){
                mApp.unblock("#select-municipios")
                $('.m-select2').select2({
                    placeholder:"Selecciona una opción",
                    language: "es"
                });
                $form.fadeIn();
            });
        });
        $(document).on('change', 'select[name=municipio_id]', function () {
            let $select = $(this);
            let municipio_id = $('select[name=municipio_id]').val();
            let $form = $("#select-colonias");
            let url = "{{ route('proveedor.load.ubicacion', [
                    'municipio_id' => 'MUNICIPIOID',
                ]) }}".replace(/&amp;/g, '&');
            url = url.replace('MUNICIPIOID', municipio_id);

            mApp.block("#select-colonias", {});

            $form.load(url, function(){
                mApp.unblock("#select-colonias")
                $('.m-select2').select2({
                    placeholder:"Selecciona una opción",
                    language: "es"
                });
                $form.fadeIn();
            });
        });
    </script>
@endpush
