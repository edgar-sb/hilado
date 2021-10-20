<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Histórico
                </h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="row">
            <div class="col-12">
                <table class="table table-striped table-bordered datatable">
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Evidencia</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($compra->estatusLog as $estatus_log)
                        <tr>
                            <td>
                                <p class="mb-0">
                                    {{ $estatus_log->created_at }}
                                </p>
                            </td>
                            <td>
                                <p class="mb-0 text-capitalize">
                                    {{ $estatus_log->user->nombre }}
                                </p>
                            </td>
                            <td>
                                <p class="mb-0">
                                    {{ $estatus_log->estatusActual->nombre }}  {{ $estatus_log->estatus }}
                                </p>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                    @switch ($estatus_log->estatusActual->clave)
                                        @case('factura')
                                        @if($estatus_log->getMedia('factura-pdf')->count() > 0)
                                            <a class="btn btn-primary btn-sm" href="{{ route('compras.download', ['compra_estatus_log' => $estatus_log, 'collection' => 'factura-pdf']) }}">
                                                Facturas PDF <i class="fa fa-download"></i>
                                            </a>
                                        @endif
                                        @if($estatus_log->getMedia('factura-xml')->count() > 0)
                                            <a class="btn btn-primary btn-sm" href="{{ route('compras.download', ['compra_estatus_log' => $estatus_log, 'collection' => 'factura-xml']) }}">
                                                Facturas XML <i class="fa fa-download"></i>
                                            </a>
                                        @endif
                                        @break
                                        @case('acuse-y-carta-porte')
                                        @if($estatus_log->getMedia('acuse')->count() > 0)
                                            <a class="btn btn-primary btn-sm" href="{{ route('compras.download', ['compra_estatus_log' => $estatus_log, 'collection' => 'acuse']) }}">
                                                Acuses <i class="fa fa-download"></i>
                                            </a>
                                        @endif
                                        @if($estatus_log->getMedia('carta')->count() > 0)
                                            <a class="btn btn-primary btn-sm" href="{{ route('compras.download', ['compra_estatus_log' => $estatus_log, 'collection' => 'carta']) }}">
                                                Cartas porte <i class="fa fa-download"></i>
                                            </a>
                                        @endif
                                        @break
                                        @case('validacion')
                                        <span>{{ $estatus_log->comentarios }}</span>
                                        @break
                                        @case('pago')
                                        @if($estatus_log->getMedia('comprobante')->count() > 0)
                                            <a class="btn btn-primary btn-sm" href="{{ route('compras.download', ['compra_estatus_log' => $estatus_log, 'collection' => 'comprobante']) }}">
                                                Comprobantes del pago <i class="fa fa-download"></i>
                                            </a>
                                        @endif
                                        @break
                                        @case('complemento')
                                        @if($estatus_log->getMedia('complemento-pdf')->count() > 0)
                                            <a class="btn btn-primary btn-sm" href="{{ route('compras.download', ['compra_estatus_log' => $estatus_log, 'collection' => 'complemento-pdf']) }}">
                                                Complementos del pago PDF <i class="fa fa-download"></i>
                                            </a>
                                        @endif
                                        @if($estatus_log->getMedia('complemento-xml')->count() > 0)
                                            <a class="btn btn-primary btn-sm" href="{{ route('compras.download', ['compra_estatus_log' => $estatus_log, 'collection' => 'complemento-xml']) }}">
                                                Complementos del pago XML <i class="fa fa-download"></i>
                                            </a>
                                        @endif
                                        @break
                                    @endswitch
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
