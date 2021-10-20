<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label>ODC sin proveedor vinculado:</label>
            {{ Form::select('select_orden_compra', $select_ordenes_compras, null, ['class' => 'form-control m-select2', 'placeholder' => '', 'id' => 'select-orden-compra']) }}
        </div>
    </div>
</div>
