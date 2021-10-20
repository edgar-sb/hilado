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
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Proveedores
                    </h3>
                </div>
            </div>
            @can('gestionar usuarios proveedores')
                <div class="m-portlet__head-tools">
                    <a href="{{ route('usuarios.proveedores.create') }}" class="btn btn-primary text-white"><i class="fas fa-plus"></i> Agregar nuevo</a>
                </div>
            @endcan
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped table-bordered datatable">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>RFC</th>
                        <th>Razón social</th>
                        <th>Ventas</th>
                        <th>Bloqueado</th>
                        @can('gestionar usuarios proveedores')
                            <th>Acciones</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                  @foreach($proveedores as $proveedor)
                      <tr>
                          <td>{{$proveedor->user->nombre}}</td>
                          <td>{{$proveedor->rfc}}</td>
                          <td>{{$proveedor->razon_social}}</td>
                          <td>{{ $proveedor->compras()->count() }}</td>
                          <td>
                              @if($proveedor->bloqueado)
                                  <span class="badge badge-danger"><i class="fas fa-check"></i></span>
                              @else
                                  <span class="badge badge-success"><i class="fas fa-times"></i></span>
                              @endif
                          </td>
                          @can('gestionar usuarios proveedores')
                              <td>
                                  <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                      <a href="{{ route('usuarios.proveedores.edit', $proveedor) }}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                      <button type="button" class="btn btn-sm btn-danger btn-delete" data-url="{{ route('usuarios.proveedores.destroy', $proveedor) }}" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
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
