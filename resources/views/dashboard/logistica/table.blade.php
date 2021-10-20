<table class="table table-striped table-bordered datatable">
    <thead>
        <tr>
            <th># ODC</th>
            <th># Factura</th>
            <th>Fecha factura</th>
            <th>Provedor</th>
            <th>Factura</th>
            <th>Monto S/IVA</th>
            <th>Fecha de ingreso</th>
            <th>Plazo de crédito</th>
            <th>Fecha de ingreso (Acuse)</th>
            <th>Acuse</th>
            <th>Validar</th>
            <th>Fecha de validación</th>
            <th>¿Proceder al pago?</th>
            <th>Fecha de pago</th>
        </tr>
    </thead>
    <tbody>
        @foreach($compras->get() as $compra)
        <tr>
            <td>{{ $compra->no_pedido }}</td>
            <td>{{ $compra->no_factura}}</td>
            <td>{{ $compra->fecha_factura }}</td>
            <td>{{ $compra->proveedor_razon_social }}</td>
            <td>{{ $compra->factura_log  }}</td>
            <td>{{ $compra->monto_format }}</td>
            <td>{{ $compra->created_at }}</td>
            <td>{{ $compra->plazo_credito }}</td>
            <td>{{ $compra->acuse_log_fecha }}</td>
            <td>{{ $compra->acuse_log }}</td>
            <td>{{ $compra->validacion_log }}</td>
            <td>{{ $compra->validacion_log_fecha }}</td>
            <td>{{ $compra->pago_log }}</td>
            <td>{{ $compra->pago_log_fecha }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
