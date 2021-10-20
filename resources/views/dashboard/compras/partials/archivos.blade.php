@switch ($compra->estatusActual->clave)
    @case('factura')
    <div class="col-12 mt-5 mb-4">
        <h4>Factura</h4>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-md-6">
                <form-dropzone-pdf :action="route('compras.upload', $compra)" :compra="$compra"></form-dropzone-pdf>
            </div>
            <div class="col-12 col-md-6">
                <form-dropzone-xml :action="route('compras.upload', $compra)" :compra="$compra"></form-dropzone-xml>
            </div>
        </div>
    </div>
    <div class="col-12" id="estatus-div">
        <div class="row">
            <div class="col-12">
                <h5>Estatus: <span id="estatus">No se han subido archivos.</span></h5>
                <p id="estatus_msg"></p>
            </div>
        </div>
    </div>
    @break
    @case('acuse-y-carta-porte')
    <div class="col-12 mt-5 mb-4">
        <h4>Acuse y Carta porte</h4>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-md-6">
                <form-dropzone :action="route('compras.upload', $compra)" :compra="$compra" nombre="Acuse" id="acuse"></form-dropzone>
            </div>
            <div class="col-12 col-md-6">
                <form-dropzone :action="route('compras.upload', $compra)" :compra="$compra" nombre="Carta porte" id="carta"></form-dropzone>
            </div>
        </div>
    </div>
    @isset($compra->rechazo_comentarios)
        <div class="col-12" id="estatus-div">
            <div class="row">
                <div class="col-12">
                    <h5>Estatus: <span id="estatus">Las evidencias de entrega han sido rechazadas.</span></h5>
                    <p id="estatus_msg">{{ $compra->rechazo_comentarios }}</p>
                </div>
            </div>
        </div>
    @else
        @isset($compra->parcial_comentarios)
            <div class="col-12" id="estatus-div">
                <div class="row">
                    <div class="col-12">
                        <h5>Estatus: <span id="estatus">Las evidencias de entrega han sido aprobadas parcialmente.</span></h5>
                        <p id="estatus_msg">{{ $compra->parcial_comentarios }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="col-12" id="estatus-div">
                <div class="row">
                    <div class="col-12">
                        <h5>Estatus: <span id="estatus">No se han subido archivos.</span></h5>
                        <p id="estatus_msg"></p>
                    </div>
                </div>
            </div>
        @endisset
    @endisset
    @break
    @case('validacion')
    <div class="col-12 mt-5 mb-4">
        <h4>Acuse y Carta porte</h4>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <a class="btn btn-primary btn-sm" href="{{ route('compras.download', ['compra_estatus_log' => $estatus_log($compra), 'collection' => 'acuse']) }}">
                        Acuses <i class="fa fa-download"></i>
                    </a>
                    <a class="btn btn-primary btn-sm" href="{{ route('compras.download', ['compra_estatus_log' => $estatus_log($compra), 'collection' => 'carta']) }}">
                        Cartas porte <i class="fa fa-download"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.compras.partials.modal-comentarios')
    @include('dashboard.compras.partials.modal-comentarios-parcial')
    @break
    @case('pago')
    <div class="col-12 mt-5 mb-4">
        <h4>Comprobante de pago</h4>
    </div>
    <div class="col-12">
        <form-dropzone :action="route('compras.upload', $compra)" :compra="$compra" nombre="Comprobante de pago" id="comprobante"></form-dropzone>
    </div>
    <div class="col-12" id="estatus-div">
        <div class="row">
            <div class="col-12">
                <h5>Estatus: <span id="estatus">No se han subido archivos.</span></h5>
                <p id="estatus_msg"></p>
            </div>
        </div>
    </div>
    @break
    @case('complemento')
    <div class="col-12 mt-5 mb-4">
        <h4>Complemento de pago</h4>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-md-6">
                <form-dropzone-pdf :action="route('compras.upload', $compra)" :compra="$compra"></form-dropzone-pdf>
            </div>
            <div class="col-12 col-md-6">
                <form-dropzone-xml :action="route('compras.upload', $compra)" :compra="$compra"></form-dropzone-xml>
            </div>
        </div>
    </div>
    <div class="col-12" id="estatus-div">
        <div class="row">
            <div class="col-12">
                <h5>Estatus: <span id="estatus">No se han subido archivos.</span></h5>
                <p id="estatus_msg"></p>
            </div>
        </div>
    </div>
    @break
@endswitch


