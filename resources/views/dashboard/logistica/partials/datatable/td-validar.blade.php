<td>
    @if($compra->validacion_log == 'Si')
        {!! Form::model($compra, ['route' => ['compras.update', 'compra' => $compra, 'path' => 'logistica'], 'method' => 'PUT', 'autocomplete' => 'off', 'id' => 'form_compra_'.$compra->id]) !!}
        {!! Form::hidden('estatus', $compra->estatusActual->id) !!}
        {!! Form::hidden('comentarios', null, ['id' => 'comentarios_hidden_'.$compra->id]) !!}
        {!! Form::hidden('comentarios-parcial', null, ['id' => 'comentarios_parcial_hidden_'.$compra->id]) !!}
        {!! Form::hidden('rechazar', false, ['id' => 'rechazar_'.$compra->id]) !!}
        {!! Form::hidden('parcial', false, ['id' => 'parcial_'.$compra->id]) !!}
        <div class="btn-group btn-group-sm btn-group-vertical">
            <button type="button" class="btn btn-sm btn-primary btn-aprobar" data-id="{{ $compra->id }}"> Aprobar</button>
            <button type="button" class="btn btn-sm btn-success btn-parcial" data-toggle="modal" data-target="#modal-comentarios-parcial" data-id="{{ $compra->id }}"> Aprobar parcialmente</button>
            <button type="button" class="btn btn-sm btn-danger btn-rechazar" data-toggle="modal" data-target="#modal-comentarios" data-id="{{ $compra->id }}"> Rechazar</button>
        </div>
        {!! Form::close() !!}
    @else
        <div class="btn-group btn-group-sm btn-group-vertical">
            <button type="button" class="btn btn-sm btn-primary" disabled> Aprobar</button>
            <button type="button" class="btn btn-sm btn-success" disabled> Aprobar parcialmente</button>
            <button type="button" class="btn btn-sm btn-danger" disabled> Rechazar</button>
        </div>
    @endif
</td>
