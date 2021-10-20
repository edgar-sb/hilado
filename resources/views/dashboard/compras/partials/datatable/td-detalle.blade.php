<p class="mb-0">
    <i class="far fa-calendar-alt"></i> {{ $compra->fecha_envio_format }}
</p>
<p class="mb-0">
    <i class="fas fa-truck-moving"></i> #{{ $compra->no_pedido }}
</p>
<p class="mb-0">
    <i class="fas fa-portrait"></i> {{ $compra->proveedor->razon_social }}
</p>
@isset($compra->no_factura)
<p class="mb-0">
    <i class="fas fa-file-invoice-dollar"></i> {{ $compra->no_factura }}
</p>
@endisset
