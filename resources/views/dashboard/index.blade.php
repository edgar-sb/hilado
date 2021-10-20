@extends('layouts.site')

@section('title')
    Inicio
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
    </style>
@endsection

@section('breadcrumb')
    <li class="m-nav__item">
        <a class="m-nav__link">
            <span class="m-nav__link-text">
                Inicio
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
                        Últimas de compras
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="w-100 pt-4 pb-4 text-right">
                @can('sincronizar odc')
                    <a href="{{ route('compras.sincroinzar') }}" class="btn btn-primary text-white mr-3 mt-2"><i class="fa fa-sync-alt"></i> Sinconizar ODC</a>
                @endcan
                @can('agregar compra')
                    <a href="{{ route('compras.create') }}" class="btn btn-primary text-white mt-2"><i class="fa fa-plus"></i> Sinconización manual</a>
                @endcan
            </div>
            <div class="row mb-5">
                <div class="col-12 col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text" style="background-color: inherit !important;">De</span>
                        <input class="form-control m-input" required="" id="fecha_inicio" name="fecha_inicio" type="date" value="2019-10-14">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="input-group mb-3">
                    <span class="input-group-text" style="background-color: inherit !important;">A</span>
                    <input class="form-control m-input" required="" id="fecha_final" name="fecha_final" type="date" value="2019-10-14">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <select class="select2 form-control">
                        <option>Selecciona proveedor</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6">
                    <select class="select2 form-control">
                        <option>Selecciona estatus</option>
                    </select>
                </div>
                <div class="col-12 text-right mt-4">
                    <button class="btn btn-success">Buscar</button>
                </div>
            </div>
            <table class="table table-striped table-bordered datatable mt-4">
                <thead>
                    <tr>
                        <th style="max-width: 200px">Detalle</th>
                        <th>Progreso</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <p class="mb-0">
                                <i class="far fa-calendar-alt"></i> Septiembre 12, 2019
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-truck-moving"></i> #982341
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-portrait"></i> Nombre del proveedor
                            </p>
                        </td>
                        <td>
                            <div class="row bs-wizard" style="border-bottom:0;">

                                <div class="col-2 bs-wizard-step complete">
                                    <div class="text-center bs-wizard-stepnum"></div>
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">
                                        <div>
                                        <i class="fas fa-file-invoice"></i>
                                        </div>
                                    </a>
                                    <div class="bs-wizard-info text-center">Venta </div>
                                </div>

                                <div class="col-2 bs-wizard-step disabled">
                                    <div class="text-center bs-wizard-stepnum"></div>
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">
                                        <div>
                                        <i class="fas fa-file-invoice-dollar"></i>
                                        </div>
                                    </a>
                                    <div class="bs-wizard-info text-center">Factura </div>
                                </div>

                                <div class="col-2 bs-wizard-step disabled">
                                    <div class="text-center bs-wizard-stepnum"></div>
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">
                                        <div>
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </div>
                                    </a>
                                    <div class="bs-wizard-info text-center">Acuse y carta porte</div>
                                </div>

                                <div class="col-2 bs-wizard-step disabled">
                                    <div class="text-center bs-wizard-stepnum"></div>
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">
                                        <div>
                                        <i class="far fa-check-circle"></i>
                                        </div>
                                    </a>
                                    <div class="bs-wizard-info text-center">Validación </div>
                                </div>

                                <div class="col-2 bs-wizard-step disabled">
                                    <div class="text-center bs-wizard-stepnum"></div>
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">
                                        <div>
                                        <i class="fas fa-money-check-alt"></i>
                                        </div>
                                    </a>
                                    <div class="bs-wizard-info text-center">Pago</div>
                                </div>

                                <div class="col-2 bs-wizard-step disabled">
                                    <div class="text-center bs-wizard-stepnum"></div>
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">
                                        <div>
                                        <i class="fas fa-file-invoice-dollar"></i>
                                        </div>
                                    </a>
                                    <div class="bs-wizard-info text-center">Complemento </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')

@endsection
