@switch ($estatus)
    @case('factura')
    <div class="col-12 mt-5 mb-4">
        <h4>Factura</h4>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-md-6">
                <form-dropzone-pdf :action="route('compras.upload.multiple', ['compras_ids' => $compras_ids, 'estatus' => $estatus])" :compra="$compra"></form-dropzone-pdf>
            </div>
            <div class="col-12 col-md-6">
                <form-dropzone-xml :action="route('compras.upload.multiple', ['compras_ids' => $compras_ids, 'estatus' => $estatus])" :compra="$compra"></form-dropzone-xml>
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
    @case('pago')
    <div class="col-12 mt-5 mb-4">
        <h4>Comprobante de pago</h4>
    </div>
    <div class="col-12">
        <form-dropzone :action="route('compras.upload.multiple', ['compras_ids' => $compras_ids, 'estatus' => $estatus])" :compra="$compra" nombre="Comprobante de pago" id="comprobante"></form-dropzone>
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
                <form-dropzone-pdf :action="route('compras.upload.multiple', ['compras_ids' => $compras_ids, 'estatus' => $estatus])" :compra="$compras_ids"></form-dropzone-pdf>
            </div>
            <div class="col-12 col-md-6">
                <form-dropzone-xml :action="route('compras.upload.multiple', ['compras_ids' => $compras_ids, 'estatus' => $estatus])" :compra="$compras_ids"></form-dropzone-xml>
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


