@if($compras->count() > 0)
<div class="row">
    <div class="col-12 table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th># ODC</th>
                <th>Provedor</th>
                <th>Fecha envío</th>
                <th>Fecha de flete</th>
                <th>Origen</th>
                <th>Monto</th>
                <th>IVA</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($compras as $compra)
                <tr>
                    <td>
                        {{ Form::hidden('compras_ids[]', $compra->id) }}
                        {{ $compra->no_pedido }}
                    </td>
                    <td>{{ $compra->proveedor->nombre }}</td>
                    <td>{{ $compra->fecha_envio }}</td>
                    <td>{{ $compra->fecha_flete }}</td>
                    <td>{{ $compra->almacen }}</td>
                    <td>${{ number_format($compra->monto / 100, 2) }}</td>
                    <td>${{ number_format($compra->impuesto/100, 2) }}</td>
                    <td>${{ number_format($compra->importe/100, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('total', 'Total', ['class' => 'col-form-label']) }}
            {{ Form::text('total', '$'.number_format($total/100, 2), ['class' => 'form-control', 'disabled', 'readonly']) }}
        </div>
    </div>
    @if($estatus == 'pago')
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('fecha_pago', 'Fecha de pago', ['class' => 'col-form-label']) }}
                {{ Form::text('fecha_pago', null, ['class' => 'form-control m-date', 'autocomplete' => 'off', 'required']) }}
            </div>
        </div>
    @endif
</div>
<div class="row">
    @include('dashboard.compras.partials.compras-multiples.archivos')
    {{ Form::hidden('estatus', $estatus) }}
</div>
@else
    <div class="row">
        <div class="col-12">
            No se encontraron órdenes de compras
        </div>
    </div>
@endif
