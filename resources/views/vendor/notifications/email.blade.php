@component('mail::message')
<div style="width: 100%; text-align: center; margin-bottom: 50px">
    <img src="{{ asset('images/logo.png') }}" alt="" class="img-fluid">
</div>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

@isset($compra)
Información de la orden de compra:
  * Proveedor: {{ $compra->proveedor->nombre }}
  * RFC del Proveedor: {{ $compra->proveedor->rfc }}
  * Número de orden de compra (ODC): # {{ $compra->no_pedido }}
@if(isset($estatus) && in_array($estatus, [
    'acuse-pendiente',
    'factura-aprobada',
    'validacion-pendiente',
    'acuse-rechazado',
    'acuse-aprobado-parcial',
    'comprobante-pendiente',
    'complemento-pago',
    ]))
  * Número de compra (OC): # {{ $compra->no_compra }}
  * Factura: {{ $compra->factura }}
@endif
  * Origen: {{ $compra->almacen }}
  * Fecha de flete: {{ $compra->fecha_flete_format }}
  * Destino: {{ $compra->destino }}
@isset($compra->maniobras)
  * Maniobras: ${{ number_format($compra->maniobras/100, 2) }}
@endisset
@isset($compra->estadia)
  * Estadía: ${{ number_format($compra->estadia/100, 2) }}
@endisset
@if(isset($estatus) && in_array($estatus, [
    'factura-aprobada',
    'validacion-pendiente',
    'acuse-rechazado',
    'acuse-aprobado-parcial',
    'comprobante-pendiente',
    'complemento-pago',
    ]))
  * Días para el vencimiento de pago: {{ $compra->dias_vencer }}
@if($compra->fecha_vencer_factura != "Factura no aprobada")
  * Fecha máxima para subir el acuse: {{ $compra->fecha_vencer_factura }}
@endif
@if($estatus == 'complemento-pago' && $compra->fecha_vencer_pago != "Pago no realizado")
  * Vencimiento pago: {{ $compra->fecha_vencer_pago }}
@endif
@isset($factura_estatus_log)
  * Fecha de aprobación de la factura: {{ $factura_estatus_log->updated_at }}
@endisset
@isset($acuse_estatus_log)
  * Fecha de aprobación del acuse: {{ $acuse_estatus_log->updated_at }}
@endisset
@isset($acuse_rechazo_estatus_log)
  * Fecha de rechazo: {{ $acuse_rechazo_estatus_log->updated_at }}
@endisset
@isset($comprobante_estatus_log)
  * Fecha de aprobación del comprobante de pago: {{ $comprobante_estatus_log->updated_at }}
@endisset
@isset($complemento_estatus_log)
  * Fecha de aprobación del complemento de pago: {{ $complemento_estatus_log->updated_at }}
@endisset
@endif

@component('mail::table')
| Servicio      | Costo         | $ IVA    | $ RET IVA   | $ Neto |
| ------------- | ------------- | --------:| -----------:| ------:|
@foreach($compra->productos as $producto)
| {{ $producto->clave }} | {{ $producto->partida_format }} | {{ $producto->iva_format }} | {{ $producto->ret_iva_format }} | {{ $producto->importe_format }} |
@endforeach
|               |               |          |             |         |
|               |               |          | Subtotal:   | ${{ number_format($compra->monto/100, 2) }} |
|               |               |          | IVA:        | ${{ number_format($compra->iva/100, 2) }} |
|               |               |          | RET IVA:    | ${{ number_format($compra->ret_iva/100, 2) }} |
|               |               |          | Total:      | ${{ number_format($compra->importe/100, 2) }} |
@endcomponent
@endisset

{{-- Action Button --}}
@isset($actionText)
<?php
    if(isset($level)){
        switch ($level) {
            case 'success':
            case 'error':
                $color = $level;
                break;
            default:
                $color = 'primary';
        }
    } else {
        $color = 'primary';
    }
?>
{{-- Action Lines --}}
@isset($actionLines)
@foreach ($actionLines as $line)
{{ $line }}
@endforeach
@endisset
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards'),<br>
{{ config('app.name') }}
@endif

@if (! empty($note))
{{ $note }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "Si usted está teniendo problemas para hacer clic en el botón  \":actionText\", copie y pegue la siguiente URL\n".
    'en su navegador web: [:actionURL](:actionURL)',
    [
        'actionText' => $actionText,
        'actionURL' => $actionUrl,
    ]
)
@endslot
@endisset
@endcomponent
