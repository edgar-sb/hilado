@extends('layouts.site')

@section('title')
    Logística
@endsection

@section('css')

@endsection

@section('breadcrumb')
    <li class="m-nav__item">
        <a href="{{ route('usuarios.logistica.index') }}" class="m-nav__link">
            <span class="m-nav__link-text">
                Logística
            </span>
        </a>
    </li>
    <li class="m-nav__separator">
        -
    </li>
    <li class="m-nav__item">
        <a class="m-nav__link">
            <span class="m-nav__link-text">
                Editar usuario de logística
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
                        Editar usuario de logística
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            {!! Form::model($user, ['route' => ['usuarios.update', $user], 'method' => 'PUT', 'autocomplete' => 'off']) !!}
            @include('dashboard.usuarios.partials.fields', ['action' => 'edit', 'rol' => 'logistica'])
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/form-repeater.js') }}" type="text/javascript"></script>
@endsection
