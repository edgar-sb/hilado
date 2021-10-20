@extends('layouts.site')

@section('title')
    Administradores
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="m-nav__item">
        <a href="{{ route('usuarios.administrador.index') }}" class="m-nav__link">
            <span class="m-nav__link-text">
                Administradores
            </span>
        </a>
    </li>
    <li class="m-nav__separator">
        -
    </li>
    <li class="m-nav__item">
        <a class="m-nav__link">
            <span class="m-nav__link-text">
                Añadir administrador
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
                        Añadir administrador
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            {!! Form::open(['route' => 'usuarios.store', 'method' => 'POST', 'autocomplete' => 'off']) !!}
                @include('dashboard.usuarios.partials.fields', ['action' => 'create', 'rol' => 'administrador'])
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/form-repeater.js') }}" type="text/javascript"></script>
@endsection
