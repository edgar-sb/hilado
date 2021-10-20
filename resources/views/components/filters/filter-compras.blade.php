<form action="" id="form-buscar">
    {!! Form::hidden('filtro', isset($filtro)? $filtro : null) !!}
    <div class="row mb-5">
        <div class="col-12 col-sm-6">
            <div class="input-group mb-3">
                <span class="input-group-text" style="background-color: inherit !important;">De</span>
                {{ Form::text('fecha_inicio', isset($fecha_inicio)? $fecha_inicio : null, ['class' => 'form-control  m-date', 'autocomplete' => 'off']) }}
            </div>
        </div>
        <div class="col-12 col-sm-6">
            <div class="input-group mb-3">
                <span class="input-group-text" style="background-color: inherit !important;">A</span>
                {{ Form::text('fecha_final', isset($fecha_final)? $fecha_final : null, ['class' => 'form-control m-date', 'autocomplete' => 'off']) }}
            </div>
        </div>
        <div class="col-12 col-sm-6  mb-3">
            {{ Form::select('proveedor', $select_proveedores, isset($proveedor)? $proveedor : null, ['class' => 'form-control m-select2']) }}
        </div>
        <div class="col-12 col-sm-6  mb-3">
            {{ Form::select('estatus', $select_estatus, isset($estatus)? $estatus : null, ['class' => 'form-control m-select2']) }}
        </div>
        <div class="col-12 col-sm-6">
            {{ Form::text('no_factura', isset($no_factura)? $no_factura : null, ['class' => 'form-control', 'placeholder' => 'Buscar factura']) }}
        </div>
        <div class="col-12 col-sm-6">
            <div class="input-group mb-3">
                <span class="input-group-text" style="background-color: inherit !important;">Del</span>
                {{ Form::text('fecha_factura_inicio', isset($fecha_factura_inicio)? $fecha_factura_inicio : null, ['class' => 'form-control m-date', 'autocomplete' => 'off', 'placeholder' => 'Fecha factura']) }}
                <span class="input-group-text" style="background-color: inherit !important;">Al</span>
                {{ Form::text('fecha_factura_final', isset($fecha_factura_final)? $fecha_factura_final : null, ['class' => 'form-control m-date', 'autocomplete' => 'off', 'placeholder' => 'Fecha factura']) }}
            </div>
        </div>
        <div class="col-12 text-right mt-4">
            <button type="submit" class="btn btn-success">Buscar</button>
        </div>
        <div class="col-12 col-md-6 mt-4">
            <div class="btn-group  btn-group-sm" data-toggle="buttons">
                <label class="btn btn-primary" style="background-color: #b502a3 !important;">
                    Ordenar:
                </label>
                <label class="btn btn-primary {{ $orden == 'desc'? 'active' : '' }}">
                    <input type="radio" class="radio-btn" name="orden" value="desc" {{ $orden == 'desc'? 'checked' : '' }} autocomplete="off" onchange="$('#form-buscar').submit()"> <i class="fa fa-arrow-down"></i>
                </label>
                <label class="btn btn-primary {{ $orden == 'asc'? 'active' : '' }}">
                    <input type="radio" class="radio-btn" name="orden" value="asc" {{ $orden == 'asc'? 'checked' : '' }} autocomplete="off" onchange="$('#form-buscar').submit()"> <i class="fa fa-arrow-up"></i>
                </label>
            </div>
        </div>
        <div class="col-12 col-md-6 mt-4 text-right">
            <div class="btn-group btn-group-sm" data-toggle="buttons">
                <label class="btn btn-primary" style="background-color: #b502a3 !important;">
                    Compras:
                </label>
                <label class="btn btn-primary {{ $finalizado == 'false'? 'active' : '' }}">
                    <input type="radio" class="radio-btn" name="finalizado" value="false" {{ $finalizado == 'false'? 'checked' : '' }} autocomplete="off" onchange="$('#form-buscar').submit()"> En proceso
                </label>
                <label class="btn btn-primary {{ $finalizado == 'true'? 'active' : '' }}">
                    <input type="radio" class="radio-btn" name="finalizado" value="true" {{ $finalizado == 'true'? 'checked' : '' }} autocomplete="off" onchange="$('#form-buscar').submit()"> Finalizadas
                </label>
            </div>
        </div>
        @if(is_null($finalizado) || $finalizado == "false")
            <div class="col-12 text-right" id="acciones-multiple" style="display: none">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger">Acciones para múltiples ODC</button>
                    <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        @can('agregar factura')
                            <a class="dropdown-item modal-compras-show" href="#" data-toggle="modal" data-target="#modal-compras" data-estatus="factura">Agregar factura</a>
                        @endcan
                        @can('agregar comprobante')
                            <a class="dropdown-item modal-compras-show" href="#" data-toggle="modal" data-target="#modal-compras" data-estatus="pago">Agregar comprobante de pago</a>
                        @endcan
                        @can('agregar complemento')
                            <a class="dropdown-item modal-compras-show" href="#" data-toggle="modal" data-target="#modal-compras" data-estatus="complemento">Agregar complemento de pago</a>
                        @endcan
                    </div>
                </div>
            </div>
        @endif
    </div>
</form>
