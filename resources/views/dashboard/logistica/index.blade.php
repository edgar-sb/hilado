@extends('layouts.site')

@section('title')
    Logística
@endsection

@section('css')
    <style>
        .radio-btn {
            display: contents;
        }
        .btn.btn-primary.active{
            background-color: #821276 !important;
        }
    </style>
@endsection

@section('breadcrumb')
    <li class="m-nav__item">
        <a class="m-nav__link">
            <span class="m-nav__link-text">
                Logística
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
                        Logística
                    </h3>
                </div>
            </div>
            @can('exportar reporte logistica')
                <div class="m-portlet__head-tools">
                    <a class="btn btn-primary text-white" onclick="descargar()"><i class="fas fa-file-export"></i> Exportar</a>
                </div>
            @endcan
        </div>
        <div class="m-portlet__body">
            @include('components.filters.filter-compras')
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped table-bordered" id="datatable-ajax">
                        <thead>
                        <tr>
                            <th># ODC</th>
                            <th># Factura</th>
                            <th>Fecha factura</th>
                            <th>Provedor</th>
                            <th>Factura</th>
                            <th>Monto S/IVA</th>
                            <th>Fecha de ingreso</th>
                            <th>Plazo de crédito</th>
                            <th>Fecha de ingreso (Acuse)</th>
                            <th>Acuse</th>
                            <th>Validar</th>
                            <th>Fecha de validación</th>
                            <th>¿Proceder al pago?</th>
                            <th>Fecha de pago</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{-- Datatable Ajax --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <modal-file></modal-file>
    @include('dashboard.compras.partials.modal-comentarios')
    @include('dashboard.compras.partials.modal-comentarios-parcial')
@endsection

@section('js')
    <script type="text/javascript">
        let data = {
            "filtro": "{!! isset($filtro)? $filtro : null !!}",
            "fecha_inicio": "{!! isset($fecha_inicio)? $fecha_inicio : null !!}",
            "fecha_final": "{!! isset($fecha_final)? $fecha_final : null !!}",
            "proveedor": "{!! isset($proveedor)? $proveedor : null !!}",
            "estatus": "{!! isset($estatus)? $estatus : null !!}",
            "orden": "{!! isset($orden)? $orden : null !!}",
            "finalizado": "{!! isset($finalizado)? $finalizado : null !!}",
            "no_factura": "{!! isset($no_factura)? $no_factura : null !!}",
            "fecha_factura_inicio": "{!! isset($fecha_factura_inicio)? $fecha_factura_inicio : null !!}",
            "fecha_factura_final": "{!! isset($fecha_factura_final)? $fecha_factura_final : null !!}",
        };

        function descargar() {
            window.location = "{{route('logistica.reporte.descargar')}}?" + $.param(data);
        }

        $('#datatable-ajax').DataTable( {
            responsive: true,
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,
            ajax: {
                url: "{{ route('logistica.datatable') }}",
                type: "GET",
                data: function ( d ) {
                    return $.extend( {}, d, data );
                }
            },
            language: {
                url: "{{ asset('js/datatables.es.json') }}"
            },
            columns: [
                { data: 'no_pedido', name: 'no_pedido' },
                { data: 'no_factura', name: 'no_factura' },
                { data: 'fecha_factura', name: 'fecha_factura' },
                { data: 'proveedor_razon_social', name: 'proveedor_razon_social' },
                { data: 'factura_log', name: 'factura_log' },
                { data: 'monto_format', name: 'monto_format' },
                { data: 'created_at', name: 'created_at' },
                { data: 'plazo_credito', name: 'plazo_credito' },
                { data: 'acuse_log_fecha', name: 'acuse_log_fecha' },
                { data: 'acuse', name: 'acuse' },
                { data: 'validar', name: 'validar' },
                { data: 'validacion_log_fecha', name: 'validacion_log_fecha' },
                { data: 'pago_log', name: 'pago_log' },
                { data: 'pago_log_fecha', name: 'pago_log_fecha' },
            ],
        }).on( 'draw', function () {
            $('[data-toggle="tooltip"]').tooltip()
        } );

        $(document).on('click', '.btn-aprobar', function() {
            $btn = $(this);
            $('#comentarios_parcial').val(null);
            $('#parcial').val(false);
            $('#comentarios').val(null);
            $('#rechazar').val(false);
            $form = $('#form_compra_' + $btn.data('id'));
            $comentarios = $('#comentarios_hidden_' + $btn.data('id'));
            $comentarios_parcial = $('#comentarios_parcial_hidden_' + $btn.data('id'));
            $rechazar = $('#rechazar_' + $btn.data('id'));
            $parcial = $('#parcial_' + $btn.data('id'));
            $comentarios.val($('#comentarios').val());
            $comentarios_parcial.val($('#comentarios_parcial').val());
            $rechazar.val($('#rechazar').val());
            $parcial.val($('#parcial').val());

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

        $(document).on('click', '#btn-parcial, #btn-rechazar', function() {
            $btn = $(this);
            $form = $('#form_compra_' + $btn.data('id'));
            $comentarios = $('#comentarios_hidden_' + $btn.data('id'));
            $comentarios_parcial = $('#comentarios_parcial_hidden_' + $btn.data('id'));
            $rechazar = $('#rechazar_' + $btn.data('id'));
            $parcial = $('#parcial_' + $btn.data('id'));
            $comentarios.val($('#comentarios').val());
            $comentarios_parcial.val($('#comentarios_parcial').val());
            $rechazar.val($('#rechazar').val());
            $parcial.val($('#parcial').val());

            $form.submit();
        });

        $(document).on('click', '.btn-rechazar', function() {
            $('#btn-rechazar').data('id', $(this).data('id'));
        });
        $(document).on('click', '.btn-parcial', function() {
            $('#btn-parcial').data('id', $(this).data('id'));
        });
    </script>
@endsection
