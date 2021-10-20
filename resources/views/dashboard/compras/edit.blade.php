@extends('layouts.site')

@section('title')
    Compras
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="m-nav__item">
        <a href="{{ route('compras.index') }}" class="m-nav__link">
            <span class="m-nav__link-text">
                Compras
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Detalle de la compra - #{{ $compra->no_pedido }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            {!! Form::model($compra, ['route' => ['compras.update', $compra], 'method' => 'PUT', 'autocomplete' => 'off', 'id' => 'form_validacion']) !!}
            {!! Form::hidden('estatus', $compra->estatusActual->id) !!}
            <div class="row">
                <div class="col-12">
                    @include('dashboard.compras.partials.fields')
                </div>
                <div class="col-12">
                    @include('dashboard.compras.partials.archivos')
                </div>
                <div class="col-12 mt-4">
                    <div class="row">
                        @role('administrador')
                            <div class="col-12 text-left">
                                <button type="button" class="btn btn-danger btn-delete" data-url="{{ route('compras.destroy', $compra) }}">Eliminar compra</button>
                            </div>
                        @endrole
                        <div class="col-12 text-right">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
                            @if($compra->estatusActual->clave == "validacion")
                                <button type="button" class="btn btn-danger btn-rechazar" data-toggle="modal" data-target="#modal-comentarios">Rechazar</button>
                                <button type="button" class="btn btn-success btn-parcial" data-toggle="modal" data-target="#modal-comentarios-parcial">Aprobación parcial</button>
                                <button type="button" class="btn btn-primary" id="aprobar">Aprobar</button>
                            @else
                                <button type="submit" class="btn btn-primary" id="btn-guardar">Guardar</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    @include('dashboard.compras.partials.historico')
@endsection

@section('js')
    <script>
        $(document).on('click', '#aprobar', function() {
            $btn = $(this);
            $('#comentarios_parcial').val(null);
            $('#parcial').val(false);
            $('#comentarios').val(null);
            $('#rechazar').val(false);
            $form = $('#form_validacion');
            swal({
                title: "¿Estás seguro/a de continuar?",
                text: "Esta acción no se puede deshacer.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger m-btn m-btn--custom",
                confirmButtonText: "¡Si, continuar!",
                cancelButtonText: "¡No, cancelar!",
            }).then( function(resp) {
                if(resp.value === true){
                    swal({
                        title: "¡Guardando información!",
                        imageUrl: "{{ asset('images/spinner.gif') }}",
                        text: 'No cierres ni actualices la página, esto puede tomar unos minutos.',
                        showCancelButton: false,
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                    $form.submit()
                }
            })
        });

        $(document).on('click', '#btn-guardar', function() {
            swal({
                title: "¡Guardando información!",
                imageUrl: "{{ asset('images/spinner.gif') }}",
                text: 'No cierres ni actualices la página, esto puede tomar unos minutos.',
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false
            });
        });
    </script>
@endsection
