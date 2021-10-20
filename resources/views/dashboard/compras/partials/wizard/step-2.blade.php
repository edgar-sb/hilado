<div class="row">
    <div class="col-12 col-sm-6">
        <p class="mb-0">Pedido no.: <span id="no_pedido"></span></p>
    </div>
    <div class="col-12 col-sm-6 text-right">
        <p class="mb-0">Fecha: <span id="fecha"></span></p>
    </div>
    <div class="col-12 mb-2">
        <hr>
        <h5>Información:</h5>
    </div>
    <div class="col-12 table-responsive">
        <table class="table table-striped">
            <tbody>
            <tr><th>Pedido SAE:</th><td id="pedido_sae"></td></tr>
            <tr><th>Fecha de flete:</th><td id="fecha_flete"></td></tr>
            <tr><th>Proveedor:</th><td id="proveedor_nombre"></td></tr>
            <tr><th>Email:</th><td id="proveedor_email"></td></tr>
            <tr><th>Razón social:</th><td id="proveedor_razon_social"></td></tr>
            <tr><th>Enviar a:</th><td id="direccion"></td></tr>
            <tr><th>RFC:</th><td id="proveedor_rfc"></td></tr>
            <tr><th>Origen:</th><td id="almacen"></td></tr>
            <tr><td colspan="2"></td></tr>
            <tr><th>Monto:</th><td id="monto"></td></tr>
            <tr><th>Impuesto:</th><td id="impuesto"></td></tr>
            <tr><th>Importe:</th><td id="importe"></td></tr>
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <hr>
        <h5>Productos:</h5>
        <br>
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="table-productos">
                <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Clave</th>
                    <th>Descripción</th>
                    <th>P/U</th>
                    <th>% Desc</th>
                    <th>Impuesto</th>
                    <th>Total partida</th>
                    <th>Importe</th>
                </tr>
                </thead>
                <tbody id="productos">
                </tbody>
            </table>
        </div>
    </div>
</div>
