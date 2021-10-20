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

@isset($compras)
Información general:
  * Proveedor: {{ $compra->proveedor->nombre }}
  * RFC del Proveedor: {{ $compra->proveedor->rfc }}
@if(isset($estatus) && in_array($estatus, [
    'acuse-pendiente',
    'factura-aprobada',
    'complemento-pendiente',
    'comprobante-pendiente',
    'compra-finalizada',
]))
  * Factura: {{ $compras->first()->factura }}
@endif
@if(isset($estatus) && in_array($estatus, [
    'factura-pendiente',
    'factura-aprobada',
    'complemento-pendiente',
    'comprobante-pendiente',
    'compra-finalizada',
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
@isset($comprobante_estatus_log)
  * Fecha de aprobación del comprobante de pago: {{ $comprobante_estatus_log->updated_at }}
@endisset
@isset($complemento_estatus_log)
  * Fecha de aprobación del complemento de pago: {{ $complemento_estatus_log->updated_at }}
@endisset
@endif

@component('mail::table')
| #OCD      | #OC       | Fecha de flete | Origen | Destino | Monto  | Impuesto   | Total  |
| --------- | --------- | -------------- | ------ | ------- | ------:| ----------:| ------:|
@foreach($compras as $compra)
| {{ $compra->no_pedido }} | {{ $compra->no_compra }} | {{ $compra->fecha_flete_format }} | {{ $compra->almacen }} | {{ $compra->destino }} | {{ $compra->monto_format }} | {{ $compra->impuesto_format }} | {{ $compra->importe_format }} |
@endforeach
|           |           |                |        |         |        |            |        |
|           |           |                |        |         |        | Monto:     | ${{ number_format($compras->sum('monto') / 100, 2) }} |
|           |           |                |        |         |        | Impuesto:  | ${{ number_format($compras->sum('impuesto') / 100, 2) }} |
|           |           |                |        |         |        | Total:     | ${{ number_format($compras->sum('importe') / 100, 2) }} |
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
