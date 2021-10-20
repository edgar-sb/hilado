@extends('layouts.site')

@section('title')
    Proveedores
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="m-nav__item">
        <a href="{{ route('usuarios.proveedores.index') }}" class="m-nav__link">
            <span class="m-nav__link-text">
                Proveedores
            </span>
        </a>
    </li>
    <li class="m-nav__separator">
        -
    </li>
    <li class="m-nav__item">
        <a class="m-nav__link">
            <span class="m-nav__link-text">
                Editar proveedor
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
                        Editar proveedor
                    </h3>
                </div>
            </div>
        </div>
        {!! Form::model($proveedor, ['route' => ['usuarios.proveedores.update', $proveedor], 'method' => 'PUT', 'autocomplete' => 'off','class' => 'col-12']) !!}
          <div class="m-portlet__body">
                @include('dashboard.proveedores.partials.fields', ['action' => 'edit'])
          </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/form-repeater.js') }}" type="text/javascript"></script>
    <script>
        $(document).on("click", ".btn-desbloquear", function() {
            let id = $(this).data("id");
            let url = "{{ route('compras.desbloquear', ':ID') }}".replace(':ID', id);
            swal({
                title: "¿Estás seguro/a de desbloquear esta compra?",
                text: "Esta acción no se puede deshacer.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger m-btn m-btn--custom",
                confirmButtonText: "¡Si, desbloquear!",
                cancelButtonText: "¡No, cancelar!",
            }).then( function(resp) {
                if(resp.value === true){
                    window.location = url;
                }
            })
        })
    </script>
@endsection
