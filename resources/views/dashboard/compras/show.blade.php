@extends('layouts.site')

@section('title')
    Compras
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="m-nav__item">
        <a href="{{ route('compras.index') }}" class="m-nav__link">
            <span class="m-nav__link-text">
                Compras
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Detalle de la compra - #{{ $compra->no_pedido }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            @if($guardar_venta)
                {!! Form::model($compra, ['route' => ['compras.update.venta', $compra], 'method' => 'PUT', 'autocomplete' => 'off']) !!}
            @else
                {!! Form::model($compra) !!}
            @endif

            <div class="row">
                <div class="col-12">
                    @include('dashboard.compras.partials.fields')
                </div>
                <div class="col-12 mt-4">
                    <div class="row">
                        @if($guardar_venta)
                            <div class="col-12 text-left">
                                <button type="button" class="btn btn-danger btn-delete" data-url="{{ route('compras.destroy', $compra) }}">Eliminar compra</button>
                            </div>
                            <div class="col-12 text-right">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary" id="btn-guardar">Guardar</button>
                            </div>
                        @else
                            @role('administrador')
                                <div class="col-12 text-left">
                                    <button type="button" class="btn btn-danger btn-delete" data-url="{{ route('compras.destroy', $compra) }}">Eliminar compra</button>
                                </div>
                            @endrole
                            <div class="col-12 text-right">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Regresar</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    @include('dashboard.compras.partials.historico')
@endsection

@section('js')
    <script>
        $(document).on('click', '#btn-guardar', function() {
            swal({
                title: "¡Guardando información!",
                imageUrl: "{{ asset('images/spinner.gif') }}",
                text: 'No cierres ni actualices la página, esto puede tomar unos minutos.',
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false
            });
        });
    </script>
@endsection
