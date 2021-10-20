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
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Adminstradores
                    </h3>
                </div>
            </div>
            @can('gestionar usuarios administrador')
            <div class="m-portlet__head-tools">
                <a href="{{route('usuarios.administrador.create')}}" class="btn btn-primary text-white"><i class="fas fa-plus"></i> Agregar nuevo</a>
            </div>
            @endcan
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped table-bordered datatable">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        @can('gestionar usuarios administrador')
                        <th>Acciones</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                  @foreach($administradores as $administrador)
                    <tr>
                        <td>{{$administrador->nombre}}</td>
                        <td>{{$administrador->email}}</td>
                        @can('gestionar usuarios administrador')
                        <td>
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <a href="{{route('usuarios.administrador.edit', $administrador)}}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-url="{{route('usuarios.destroy', $administrador)}}" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')

@endsection
