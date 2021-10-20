@extends('layouts.site')

@section('title')
    Compras
@endsection

@section('css')
    <style>
        /*Form Wizard*/
        .bs-wizard {border-bottom: solid 1px #e0e0e0; padding: 0 0 10px 0;}
        .bs-wizard > .bs-wizard-step {padding: 0; position: relative;}
        .bs-wizard > .bs-wizard-step + .bs-wizard-step {}
        .bs-wizard > .bs-wizard-step .bs-wizard-stepnum {color: #595959; font-size: 16px; margin-bottom: 5px; height: 20px}
        .bs-wizard > .bs-wizard-step .bs-wizard-info {color: #999; font-size: 10px;}
        .bs-wizard > .bs-wizard-step > .bs-wizard-dot {position: absolute;width: 50px;height: 50px;display: block;background: #5867dd;top: 50%;left: 0;right: 0;margin: auto;border-radius: 50%;transform: translateY(-50%);}
        .bs-wizard > .bs-wizard-step > .bs-wizard-dot > div{position: relative; width: 50px;height: 50px;}
        .bs-wizard > .bs-wizard-step > .bs-wizard-dot > div > i{color:#ffffff;position: absolute;left: 50%;margin: auto;top: 50%;transform: translate(-50%, -50%);font-size: 26px;z-index: 100;}
        .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot > div > i{color:#5867dd;}
        .bs-wizard > .bs-wizard-step > .progress {position: relative; border-radius: 0px; height: 8px; box-shadow: none; margin: 20px 0;}
        .bs-wizard > .bs-wizard-step > .progress > .progress-bar {width:0px; box-shadow: none; background: #5867dd;}
        .bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {width:100%;}
        .bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {width:50%;}
        .bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {width:0%;}
        .bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {width: 100%;}
        .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {background-color: #e9ecef;}
        .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {opacity: 0;}
        .bs-wizard > .bs-wizard-step:first-child  > .progress {left: 50%; width: 50%;}
        .bs-wizard > .bs-wizard-step:last-child  > .progress {width: 50%;}
        .bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot{ pointer-events: none; }
        .bs-wizard > .bs-wizard-step.danger > .bs-wizard-dot {background-color: #f10000;}
        .decoration-none{
            text-decoration: none !important;
        }
        .radio-btn {
            display: contents;
        }
        .btn.btn-primary.active{
            background-color: #821276 !important;
        }
    </style>
@endsection

@section('breadcrumb')
    @isset($titulo)
        <li class="m-nav__item">
            <a class="m-nav__link">
            <span class="m-nav__link-text">
                Compras
            </span>
            </a>
        </li>
    @else
        <li class="m-nav__item">
            <a class="m-nav__link">
            <span class="m-nav__link-text">
                Inicio
            </span>
            </a>
        </li>
    @endisset
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        @isset($titulo)
                            {{ $titulo }}
                        @else
                            Últimas de compras
                        @endisset
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="w-100 pt-4 pb-4 text-right">
                @can('sincronizar odc')
                    <button data-url="{{ route('compras.sincroinzar') }}" id="sincronizar" class="btn btn-primary text-white mr-3 mt-2"><i class="fa fa-sync-alt"></i> Sinconizar ODC</button>
                @endcan
                @can('agregar compra')
                    <a href="{{ route('compras.create') }}" class="btn btn-primary text-white mt-2"><i class="fa fa-plus"></i> Sinconización manual</a>
                @endcan
            </div>
            @include('components.filters.filter-compras')
            <div class="table-responsive">
                <table class="table table-striped table-bordered mt-4" id="datatable-ajax">
                    <thead>
                    <tr>
                        <th style="width: 50px"></th>
                        <th style="max-width: 200px">Detalle</th>
                        <th>Progreso</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{-- Datatable Ajax --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('dashboard.compras.partials.compras-multiples.modal-compras')
@endsection

@section('js')
    <script>
        let agrupaciones_ids = @json($agrupaciones_ids);
        let compras_ids = [];
        $(document).on('change', '.check-compra', function() {
            $check = $(this);
            if($check.is(':checked')) {
                let id = $check.data('id');
                compras_ids.push($check.data('id'));
                if(compras_ids.length <= 1) {
                    Object.keys(agrupaciones_ids).forEach(function (item) {
                        if (agrupaciones_ids[item].includes(id)) {
                            agrupaciones_ids[item].forEach(element => {
                                compras_ids.push(element)
                                $('input[data-id=' + element + ']').prop('checked', true)
                            })
                        }
                    });
                }
            } else {
                let id = $check.data('id');
                compras_ids.splice(compras_ids.indexOf(id), 1 );
                if(compras_ids.length <= 1) {
                    Object.keys(agrupaciones_ids).forEach(function (item) {
                        if (agrupaciones_ids[item].includes(id)) {
                            agrupaciones_ids[item].forEach(element => {
                                compras_ids.splice(compras_ids.indexOf(element), 1);
                                $('input[data-id=' + element + ']').prop('checked', false)
                            });
                        }
                    });
                }
            }
            compras_ids.filter((item, index) => compras_ids.indexOf(item) === index);
            if(compras_ids.length > 0) {
                $('#acciones-multiple').fadeIn();
            } else {
                $('#acciones-multiple').fadeOut();
            }
        });
        $('#datatable-ajax').DataTable( {
            responsive: true,
            processing: true,
            serverSide: true,
            ordering: false,
            searching: false,
            ajax: {
                url: "{{ route('compras.datatable') }}",
                type: "GET",
                data: function ( d ) {
                    return $.extend( {}, d, {
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
                    } );
                }
            },
            language: {
                url: "{{ asset('js/datatables.es.json') }}"
            },
            columns: [
                { data: 'check', name: 'check' },
                { data: 'detalle', name: 'detalle' },
                { data: 'progreso', name: 'progreso' },
            ],
        }).on( 'draw', function () {
            $('[data-toggle="tooltip"]').tooltip()
            compras_ids = [];
            $('#acciones-multiple').fadeOut();
        } );

        $('#sincronizar').on('click', function() {
            $btn = $(this);
            swal({
                title: "¿Estás seguro/a de continuar?",
                text: "Esta acción no se puede deshacer.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger m-btn m-btn--custom",
                confirmButtonText: "¡Si, continuar!",
                cancelButtonText: "¡No, cancelar!"
            }).then( function(resp) {
                if(resp.value === true){
                    swal({
                        title: "¡Sincronizando!",
                        imageUrl: "{{ asset('images/spinner.gif') }}",
                        text: 'No cierres ni actualices la página, esto puede tomar unos minutos.',
                        showCancelButton: false,
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });
                    window.location = $btn.data('url');
                }
            })
        });
    </script>
@endsection
