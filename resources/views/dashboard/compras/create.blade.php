@extends('layouts.site')

@section('title')
    Compras
@endsection

@section('css')
    <link href="{{ asset('css/smart_wizard.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/smart_wizard_theme_arrows.css') }}" rel="stylesheet" />
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
                        Nueva compra
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="smartwizard">
                <ul>
                    <li id="nav-step-0"><a href="#step-1">Seleccionar ODC</a></li>
                    <li id="nav-step-1"><a href="#step-2">Validar información</a></li>
                    <li id="nav-step-2"><a href="#step-3">Confirmar</a></li>
                </ul>

                <div>
                    <div id="step-1" class="">
                        @include('dashboard.compras.partials.wizard.step-1')
                    </div>
                    <div id="step-2" class="">
                        @include('dashboard.compras.partials.wizard.step-2')
                    </div>
                    <div id="step-3" class="">
                        @include('dashboard.compras.partials.wizard.step-3')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/jquery.smartWizard.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var $smartwizard = $('#smartwizard');
            var $select_orden_compra = $('#select-orden-compra');

            $smartwizard.smartWizard({
                selected: 0,
                useURLhash: false,
                theme: "arrows",
                lang:{
                    next: "Siguiente",
                    previous: "Anterior"
                },
                toolbarSettings: {
                    toolbarPosition: 'bottom', // none, top, bottom, both
                    toolbarButtonPosition: 'right', // left, right
                    showNextButton: true, // show/hide a Next button
                    showPreviousButton: true, // show/hide a Previous button
                }
            });
            $smartwizard.on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
                if ($select_orden_compra.val() === null || $select_orden_compra.val() === undefined || $select_orden_compra.val() === "") {
                    e.preventDefault();
                    swal({
                        title: "Atención",
                        text: 'Selecciona una orden de compra',
                        type: 'warning',
                        confirmButtonText: 'Entendido'
                    });
                }
            });
            $smartwizard.on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
                if (stepDirection === "backward") {
                    for ($i=stepNumber+1; $i<=2; $i++) {
                        $('#nav-step-'+$i).removeClass('done');
                    }
                }
            });

            $select_orden_compra.on('change', function() {
                mApp.block("#smartwizard", {});
                $.ajax({
                    method: "GET",
                    url: "{{ route('api.sae.get.odc') }}",
                    data: {cve_doc: $select_orden_compra.val()}
                })
                    .done(function (response) {
                        updateInfo(response)
                        mApp.unblock("#smartwizard", {});
                    })
                    .fail(function (response) {
                        mApp.unblock("#smartwizard", {});
                        swal({
                            title: "¡Error!",
                            text: 'No se ha podido cargar la compra',
                            type: 'error',
                            confirmButtonText: 'Entendido'
                        });
                    });
            });

            function updateInfo(orden) {
                if(orden) {
                    $('#btn-store-orden-compra').data('cve_doc', orden.CVE_DOC);
                    $('#no_pedido').text(orden.CVE_DOC);
                    $('#pedido_sae').text(orden.pedido_sae);
                    $('#fecha').text(orden.fecha_format);
                    $('#fecha_flete').text(orden.fecha_flete_format);
                    $('#proveedor_nombre').text(orden.proveedor.NOMBRE);
                    $('#proveedor_email').text(orden.proveedor.EMAILPRED);
                    $('#proveedor_razon_social').text(orden.proveedor.NOMBRE);
                    $('#direccion').text(orden.OBS_COND);
                    $('#proveedor_rfc').text(orden.proveedor.RFC);
                    $('#almacen').text(orden.almacen.DESCR);
                    $('#monto').text(orden.monto_format);
                    $('#impuesto').text(orden.impuesto_format);
                    $('#importe').text(orden.importe_format);
                    $('#email').val(orden.proveedor.EMAILPRED);

                    orden.productos.forEach(producto => {
                        if(producto.CVE_ART === ' MANIOBRAS') {
                            $('#maniobras').val(parseFloat(producto.COST).toFixed(2));
                        }
                        if(producto.CVE_ART === 'Estadía') {
                            $('#estadia').val(parseFloat(producto.COST).toFixed(2));
                        }
                    });


                    $('#table-productos').DataTable({
                        destroy: true,
                        data: orden.productos,
                        responsive: true,
                        info: false,
                        language: {
                            url: "{{ asset('js/datatables.es.json') }}"
                        },
                        columns: [
                            {data: 'cantidad_format'},
                            {data: 'CVE_ART'},
                            {data: 'producto.DESCR'},
                            {data: 'precio_format'},
                            {data: 'descuento_format'},
                            {data: 'impuesto_format'},
                            {data: 'partida_format'},
                            {data: 'importe_format'},
                        ]
                    });
                }
            }
        });
    </script>
@endsection
