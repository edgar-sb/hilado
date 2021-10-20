<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title') | {{ config('app.name') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        <script>
            WebFont.load({
                google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
                active: function() {
                    sessionStorage.fonts = true;
                }
            });
        </script>

        {{--<link rel="manifest" href="/manifest.json" />--}}
        {{-- <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
        <script>
            var OneSignal = window.OneSignal || [];
            OneSignal.push(function() {
                OneSignal.init({
                    appId: "{{ env('ONESIGNAL_APP_ID') }}",
                });
            });
        </script>--}}


        <link href="{{ asset('css/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />

        <!--RTL version:<link href="{{ asset('css/vendors.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />-->
        <link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

        <!--RTL version:<link href="{{ asset('css/style.bundle.rtl.css') }}" rel="stylesheet" type="text/css" />-->
        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fullcalendar/fullcalendar.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fullcalendar/fullcalendar.print.css') }}" media='print'>
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.bundle.css') }}"/>

        <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/inputgroup.css') }}" rel="stylesheet" />

        <link rel="shortcut icon" href="" />


        <style>
            .btn-success{
                background: #9f58dd !important;
                border-color: #9f58dd !important;
            }

            .btn-success:hover{
                background: #ba5fff !important;
                border-color: #ba5fff !important;
            }

            .btn-info{
                background: #713f9d !important;
                border-color: #713f9d !important;
            }

            .btn-info:hover{
                background: #9048c4 !important;
                border-color: #9048c4 !important;
            }

            .btn-danger{
                background: #87419d !important;
                border-color: #87419d !important;
            }

            .btn-danger:hover{
                background: #a845be !important;
                border-color: #a845be !important;
            }

            .btn-primary{
                background: #9d138f !important;
                border-color: #9d138f !important;
            }

            .btn-primary:hover{
                background: #c613b8 !important;
                border-color: #c613b8 !important;
            }
        </style>

        @yield('css')
        @stack('styles')

    </head>
    <!-- end::Head -->

    <!-- begin::Body -->
    <body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-page--loading-enabled m-page--loading m-aside-left--skin-light m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

        <!-- begin::Page loader -->
        <div class="m-page-loader m-page-loader--base">
            <div class="m-blockui">
                <span>Cargando...</span>
                <span>
                    <div class="m-loader m-loader--brand"></div>
                </span>
            </div>
        </div>

        <!-- begin:: Page -->
        <div class="m-grid m-grid--hor m-grid--root m-page">

            <!-- BEGIN: Header -->
            <header id="m_header" class="m-grid__item m-header " m-minimize-offset="200" m-minimize-mobile-offset="200">
                <div class="m-container m-container--fluid m-container--full-height">
                    <div class="m-stack m-stack--ver m-stack--desktop">
                        <!-- BEGIN: Brand -->
                        <div class="m-stack__item m-brand  m-brand--skin-light ">
                            <div class="m-stack m-stack--ver m-stack--general">
                                <div class="m-stack__item m-stack__item--middle m-brand__logo">
                                    <a href="@can('ver compras'){{ route('inicio') }}@endcan" class="m-brand__logo-wrapper">
                                        <img alt="" src="{{ asset('images/logo.png') }}" style="width: 150px;"/>
                                    </a>
                                </div>
                                <div class="m-stack__item m-stack__item--middle m-brand__tools">

                                    <!-- BEGIN: Left Aside Minimize Toggle -->
                                    <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block  ">
                                        <span></span>
                                    </a>

                                    <!-- END -->

                                    <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                                    <a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                                        <span></span>
                                    </a>

                                    <!-- END -->

                                    <!-- BEGIN: Topbar Toggler -->
                                    <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                                        <i class="flaticon-more"></i>
                                    </a>

                                    <!-- BEGIN: Topbar Toggler -->
                                </div>
                            </div>
                        </div>

                        <!-- END: Brand -->
                        <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">

                            <!-- BEGIN: Horizontal Menu -->
                            <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
                            <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark ">
                                <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                                    <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" m-menu-link-redirect="1" aria-haspopup="true">
                                        <a class="m-menu__link m-menu__toggle" title="Non functional dummy link">
                                            <img src="{{ asset('images/user.jpeg') }}" class="m--img-rounded m--marginless" alt="" style="height: 50px; margin-top: 10px !important; margin-right: 10px !important;" />
                                            <p class="m-menu__link-text"><b>{{ auth()->user()->nombre }} </b></p>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                            <!-- END: Horizontal Menu -->

                            <!-- BEGIN: Topbar -->
                            <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid">
                                <div class="m-stack__item m-topbar__nav-wrapper">
                                    <ul class="m-topbar__nav m-nav m-nav--inline">
                                        <li class="m--hidden-desktop m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" m-dropdown-toggle="click">
                                            <a class="m-nav__link m-dropdown__toggle">
                                                <span class="m-topbar__userpic">
                                                    <img src="{{ asset('images/user.jpeg') }}" class="m--img-rounded m--marginless" alt="" />
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- END: Topbar -->
                        </div>
                    </div>
                </div>
            </header>

            <!-- END: Header -->

            <!-- begin::Body -->
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

                <!-- BEGIN: Left Aside -->
                <button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
                    <i class="la la-close"></i>
                </button>
                <div id="m_aside_left" class="m-grid__item	m-aside-left   m-aside-left--skin-light ">

                    <!-- BEGIN: Aside Menu -->
                    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-light m-aside-menu--submenu-skin-light " m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
                        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
                            <li class="m-menu__section ">
                                <h4 class="m-menu__section-text">Menú</h4>
                                <i class="m-menu__section-icon flaticon-more-v2"></i>
                            </li>
                            @can('ver compras')
                            <li class="m-menu__item" aria-haspopup="true">
                                <a href="{{ route('inicio') }}" class="m-menu__link m-menu__toggle">
                                    <i class="m-menu__link-icon fas fa-home"></i>
                                    <span class="m-menu__link-text">Inicio</span>
                                </a>
                            </li>
                            @endcan
                            @can('ver compras')
                            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                                <a href="javascript:;" class="m-menu__link m-menu__toggle">
                                    <i class="m-menu__link-icon fas fa-money-check"></i>
                                    <span class="m-menu__link-text">Compras</span>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true">
                                            <span class="m-menu__link">
                                                <span class="m-menu__link-text">Compras</span>
                                            </span>
                                        </li>
                                        <li class="m-menu__item" aria-haspopup="true">
                                            <a href="{{ route('compras.index', ['filtro' => 'todas']) }}" class="m-menu__link ">
                                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                    <span></span>
                                                </i>
                                                <span class="m-menu__link-text">Todas</span>
                                            </a>
                                        </li>
                                        <li class="m-menu__item " aria-haspopup="true">
                                            <a href="{{ route('compras.index', ['filtro' => 'en_proceso']) }}" class="m-menu__link ">
                                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                    <span></span>
                                                </i>
                                                <span class="m-menu__link-text">En proceso</span>
                                            </a>
                                        </li>
                                        <li class="m-menu__item " aria-haspopup="true">
                                            <a href="{{ route('compras.index', ['filtro' => 'por_pagar']) }}" class="m-menu__link ">
                                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                    <span></span>
                                                </i>
                                                <span class="m-menu__link-text">Por pagar</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            @endcan
                            @can('ver reporte finanzas')
                                <li class="m-menu__item" aria-haspopup="true">
                                    <a href="{{ route('finanzas') }}" class="m-menu__link m-menu__toggle">
                                        <i class="m-menu__link-icon fas fa-file-invoice"></i>
                                        <span class="m-menu__link-text">Finanzas</span>
                                    </a>
                                </li>
                            @endcan
                            @can('ver reporte logistica')
                                <li class="m-menu__item" aria-haspopup="true">
                                    <a href="{{ route('logistica') }}" class="m-menu__link m-menu__toggle">
                                        <i class="m-menu__link-icon fas fa-clipboard-list"></i>
                                        <span class="m-menu__link-text">Logística</span>
                                    </a>
                                </li>
                            @endcan
                            @can('ver usuarios proveedores')
                            <li class="m-menu__item" aria-haspopup="true">
                                <a href="{{ route('usuarios.proveedores.index') }}" class="m-menu__link m-menu__toggle">
                                    <i class="m-menu__link-icon fas fa-user"></i>
                                    <span class="m-menu__link-text">Proveedores</span>
                                </a>
                            </li>
                            @endcan
                            @can('ver usuarios administrador', 'ver usuarios logistica', 'ver usuarios contabilidad')
                            <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
                                <a href="javascript:;" class="m-menu__link m-menu__toggle">
                                    <i class="m-menu__link-icon fas fa-users"></i>
                                    <span class="m-menu__link-text">Usuarios</span>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                                    <ul class="m-menu__subnav">
                                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true">
                                            <span class="m-menu__link">
                                                <span class="m-menu__link-text">Usuarios</span>
                                            </span>
                                        </li>
                                        @can('ver usuarios administrador')
                                        <li class="m-menu__item" aria-haspopup="true">
                                            <a href="{{route('usuarios.administrador.index')}}" class="m-menu__link ">
                                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                    <span></span>
                                                </i>
                                                <span class="m-menu__link-text">Administrador</span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('ver usuarios logistica')
                                        <li class="m-menu__item " aria-haspopup="true">
                                            <a href="{{ route('usuarios.logistica.index') }}" class="m-menu__link ">
                                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                    <span></span>
                                                </i>
                                                <span class="m-menu__link-text">Logística</span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('ver usuarios contabilidad')
                                        <li class="m-menu__item " aria-haspopup="true">
                                            <a href="{{ route('usuarios.contabilidad.index') }}" class="m-menu__link ">
                                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                    <span></span>
                                                </i>
                                                <span class="m-menu__link-text">Contabilidad</span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                            @endcan
                            <li class="m-menu__item" aria-haspopup="true">
                                <a href="{{ route('logout') }}" class="m-menu__link m-menu__toggle"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="m-menu__link-icon fas fa-sign-out-alt"></i>
                                    <span class="m-menu__link-text">Cerrar sesión</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                    <!-- END: Aside Menu -->

                </div>

                <!-- END: Left Aside -->
                <div class="m-grid__item m-grid__item--fluid m-wrapper">
                    <!-- BEGIN: Subheader -->
                    <div class="m-subheader">
                        <div class="d-flex align-items-center">
                            <div class="mr-auto">
                                <h3 class="m-subheader__title m-subheader__title--separator">@yield('title')</h3>

                                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                                    <li class="m-nav__item m-nav__item--home">
                                        <a href="{{ route('inicio') }}" class="m-nav__link m-nav__link--icon">
                                            <i class="m-nav__link-icon la la-home"></i>
                                        </a>
                                    </li>
                                    <li class="m-nav__separator">
                                        -
                                    </li>
                                    @yield('breadcrumb')
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- END: Subheader -->
                    <div class="m-content">
                        <div class="m-portlet__body">
                            <div class="m-wizard m-wizard--5 m-wizard--danger">
                                <!--begin: Message container -->
                                <div class="m-portlet__padding-x" id="errors">
                                    @if ($errors->any())
                                        <div class="m-alert m-alert--outline alert alert-danger alert-dismissible" role="alert">
                                            <strong>Corrige los siguientes errores:</strong>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
            <!-- end:: Body -->

            <!-- begin::Footer -->
            <footer class="m-grid__item		m-footer ">
                <div class="m-container m-container--fluid m-container--full-height m-page__container">
                    <div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
                        <div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
                            <span class="m-footer__copyright">
                                {{ date('Y', time()) }} &copy; {{ config('app.name') }}</a>
                            </span>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end::Footer -->
            {{ Form::open(['url' => ':url', 'method' => 'DELETE', 'id' => 'form-delete']) }}
            {{ Form::close() }}
        </div>
        <!-- end:: Page -->

        <!-- begin::Scroll Top -->
        <div id="m_scroll_top" class="m-scroll-top">
            <i class="la la-arrow-up"></i>
        </div>
        <!-- end::Scroll Top -->

        <!--begin::Global Theme Bundle -->
        <script src="{{ asset('js/vendors.bundle.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/scripts.bundle.js') }}" type="text/javascript"></script>
        <!--end::Global Theme Bundle -->

        <!--begin::Page Scripts -->
        <script src="{{ asset('js/dashboard.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/datatables.bundle.js') }}" type="text/javascript"></script>
        <!-- Lenguajes -->
        <script src="{{ asset('plugins/select2/i18n/es.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/datepicker.es.js') }}" type="text/javascript"></script>

        <script>
            //Loading
            $(window).on('load', function() {
                $('body').removeClass('m-page--loading');
            });
            //AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip()

            $('.datatable').DataTable( {
                responsive: true,
                info: false,
                language: {
                    url: "{{ asset('js/datatables.es.json') }}"
                },
                "columnDefs": [ {
                    "targets"  : 'no-sort',
                    "orderable": false,
                    "order": []
                }]
            });

            //Select2
            $('.m-select2').select2({
                placeholder:"Selecciona una opción",
                language: "es"
            });

            $('.m-select2--multiple').select2({
                placeholder:"Selecciona...",
                language: "es"
            });

            //Datepicker
            // Para startDate y endDate poner como atributo 'data-date-start-date="0" 'data-date-end-date="0"'
            $('.m-date').datepicker({
                todayHighlight: !0,
                orientation: "bottom left",
                format: 'yyyy/mm/dd',
                language: "es",
                clearBtn: true,
                templates: {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            });

            $('.m-date--today').datepicker('update', new Date())

            //Botón delete
            $(document).on('click', '.btn-delete', function(e) {
                $form = document.getElementById('form-delete')
                $form.action = $(this).data('url')

                swal({
                    title: "¿Estás seguro/a de continuar?",
                    text: "Esta acción no se puede deshacer.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn btn-danger m-btn m-btn--custom",
                    confirmButtonText: "¡Si, eliminar!",
                    cancelButtonText: "¡No, cancelar!",
                }).then( function(resp) {
                    if(resp.value === true){
                        $form.submit()
                    }
                })
            });

            //Errors
            function showErrors (errors) { //Array de errores
                var errors_msg = '<div class="m-alert m-alert--outline alert alert-danger alert-dismissible" role="alert"><strong>Corrige los siguientes errores:</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"></button><ul>';
                errors.forEach(function (error) {
                    errors_msg = errors_msg + '<li>' + error[0] + '</li>';
                });
                errors_msg = errors_msg + '</ul></div>';
                $('#errors').html(errors_msg);
            }

            //Sweet alert
            @if(session('success'))
            swal({
                title: "¡Bien hecho!",
                html: "{!! session('success') !!}",
                type: 'success',
                confirmButtonText: 'Entendido'
            });
            @endif

            @if(session('warning'))
            swal({
                title: "¡Atención!",
                html: '{!! session('warning') !!}',
                type: 'warning',
                confirmButtonText: 'Entendido'
            });
            @endif

            @if(session('info'))
            swal({
                title: "¡Información!",
                html: '{!! session('info') !!}',
                type: 'info',
                confirmButtonText: 'Entendido'
            });
            @endif

            @if(session('error'))
            swal({
                title: "¡Error!",
                html: '{!! session('error') !!}',
                type: 'error',
                confirmButtonText: 'Entendido'
            });
            @endif

        </script>

        @yield('js')
        @stack('scripts')

        <!--end::Page Scripts -->
    </body>
    <!-- end::Body -->

</html>
