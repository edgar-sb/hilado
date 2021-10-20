<div class="form-group row">
    <label for="fecha_creacion" class="col-12 col-sm-3 col-md-4 col-form-label">Fecha de creación:</label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="fecha_creacion" value="{{ $compra->created_at }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="no_pedido" class="col-12 col-sm-3 col-md-4 col-form-label"># de orden de compra: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="no_pedido"  value="{{ $compra->no_pedido }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="no_orden" class="col-12 col-sm-3 col-md-4 col-form-label">Pedido SAE: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="no_orden"  value="{{ $compra->pedido_sae }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="no_compra" class="col-12 col-sm-3 col-md-4 col-form-label"># de compra: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="no_compra"  value="{{ $compra->no_compra }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="no_compra" class="col-12 col-sm-3 col-md-4 col-form-label"># factura: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="no_factura"  value="{{ $compra->no_factura }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="no_compra" class="col-12 col-sm-3 col-md-4 col-form-label">Fecha factura: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="fecha_factura"  value="{{ $compra->fecha_factura }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="dias_vencer" class="col-12 col-sm-3 col-md-4 col-form-label">Días para el vencimiento de pago: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="dias_vencer"  value="{{ $compra->dias_vencer }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="proveedor" class="col-12 col-sm-3 col-md-4 col-form-label">Proveedor: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="proveedor" value="{{ optional($compra->proveedor)->razon_social? optional($compra->proveedor)->razon_social : optional($compra->proveedor)->nombre }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="proveedor_rfc" class="col-12 col-sm-3 col-md-4 col-form-label">Proveedor RFC: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="proveedor_rfc"  value="{{ $compra->proveedor->rfc }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="fecha_envio" class="col-12 col-sm-3 col-md-4 col-form-label">Fecha de envío: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="fecha_envio" value="{{ $compra->fecha_envio }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="fecha_flete" class="col-12 col-sm-3 col-md-4 col-form-label">Fecha de flete: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="fecha_flete" value="{{ $compra->fecha_flete }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="destino" class="col-12 col-sm-3 col-md-4 col-form-label">Destino: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="destno" value="{{ $compra->destino }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="almacen" class="col-12 col-sm-3 col-md-4 col-form-label">Origen: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="almacen" value="{{ $compra->almacen }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="monto" class="col-12 col-sm-3 col-md-4 col-form-label">Monto: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="monto"  value="${{ number_format($compra->monto/100, 2) }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="iva" class="col-12 col-sm-3 col-md-4 col-form-label">IVA: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="iva" value="${{ number_format($compra->impuesto/100, 2) }}" readonly disabled>
    </div>
</div>
<div class="form-group row">
    <label for="total" class="col-12 col-sm-3 col-md-4 col-form-label">Total: </label>
    <div class="col-12 col-sm-9 col-md-8">
        <input type="text" class="form-control" name="total"  value="${{ number_format($compra->importe/100, 2) }}" readonly disabled>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group row">
            <label for="maniobras" class="col-12 col-sm-3 col-md-4 col-form-label">Maniobras: </label>
            <div class="col-12 col-sm-9 col-md-8">
                {{ Form::number('maniobras', $compra->maniobras/100, ['class' => 'form-control', $guardar_venta? '' : 'readonly disabled']) }}
            </div>
        </div>
        <div class="form-group row">
            <label for="estadia" class="col-12 col-sm-3 col-md-4 col-form-label">Estadía: </label>
            <div class="col-12 col-sm-9 col-md-8">
                {{ Form::number('estadia', $compra->estadia/100, ['class' => 'form-control', $guardar_venta? '' : 'readonly disabled']) }}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group row">
            <label for="fecha_pago" class="col-12 col-sm-3 col-md-4 col-form-label">Fecha de pago: </label>
            <div class="col-12 col-sm-9 col-md-8">
                <input type="text" class="form-control m-date" name="fecha_pago" value="{{ $compra->fecha_pago }}" {{ $compra->progreso('pago') != 'active'? 'readonly disabled' : '' }}>
            </div>
        </div>
    </div>
</div>
