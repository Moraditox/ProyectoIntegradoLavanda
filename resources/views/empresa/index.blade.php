@extends('layouts.app')

@section('template_title')
    Empresa
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Empresas') }}
                            </span>

                            <div class="float-right w-50">
                                <form action="{{ route('empresas.index') }}" method="GET" class="form-inline my-2 my-lg-0">
                                    <div class="input-group w-100">
                                        <input class="form-control mr-sm-2" type="search" name="search"
                                            placeholder="{{ __('Buscar') }}" aria-label="{{ __('Buscar') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-success my-2 my-sm-0"
                                                type="submit">{{ __('Buscar') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="float-right">
                                <a href="{{ route('empresas.create') }}" class="btn btn-primary btn-sm float-right"
                                    data-placement="left">
                                    {{ __('Añadir') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-2">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger m-2">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>Logo</th>
                                        <th>Empresa</th>
                                        <th>Persona contacto</th>
                                        <th>Email de Contacto</th>
                                        <th>Teléfono de Contacto</th>
                                        <th>Acciones</th>
                                        <!-- <th>Invitar a Convocatoria</th>
                                        <th>Formulario Seguimiento</th>
                                        <th>Informe</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($empresas as $empresa)
                                        <tr>
                                            <td><img src="{{ asset('storage/logos/' . $empresa->logo) }}" alt="Logo"
                                                style="max-width: 90px;"></td>
                                            <td>{{ $empresa->nombre }}</td>
                                            <td>{{ $empresa->persona_contacto }}</td>
                                            <td>{{ $empresa->correo_contacto }}</td>
                                            <td>{{ $empresa->telefono_contacto }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('empresas.show', $empresa->id) }}">
                                                    <i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}
                                                </a>
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('empresas.edit', $empresa->id) }}">
                                                    <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                
                                                    data-target="#confirmModal{{ $empresa->id }}">
                                                    <i class="fa fa-fw fa-trash"></i> {{ __('Borrar') }}
                                                </button>
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('empresas.addConvocatoria', $empresa->id) }}">
                                                    <i class="fa fa-fw fa-edit"></i> {{ __('Añadir a convocatoria') }}
                                                </a>
                                                <!-- Modal de confirmación eliminación -->
                                                <div class="modal fade" id="confirmModal{{ $empresa->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="confirmModalLabel{{ $empresa->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="confirmModalLabel{{ $empresa->id }}">Confirmar
                                                                    eliminación</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body" style="text-align: center">
                                                                ¿Está seguro de que desea eliminar la empresa
                                                                <code>{{ $empresa->nombre }}</code>?
                                                                <br>
                                                                <img src="{{ asset('storage/logos/' . $empresa->logo) }}"
                                                                    alt="Logo" style="max-width: 50%">
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Cancelar</button>
                                                                <form
                                                                    action="{{ route('empresas.destroy', $empresa->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Eliminar</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <!-- <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#correoInvitacionModal{{ $empresa->id }}">
                                                    <i class="fa fa-fw fa-trash"></i> {{ __('Enviar') }}
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#correoSeguimientoModal{{ $empresa->id }}">
                                                    <i class="fa fa-fw fa-trash"></i> {{ __('Enviar') }}
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                    data-target="#correoInformeModal{{ $empresa->id }}">
                                                    <i class="fa fa-fw fa-trash"></i> {{ __('Enviar') }}
                                                </button>
                                            </td> -->
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Preguntar a José si quitar esto -->
                {!! $empresas->links() !!}
            </div>
        </div>
    </div>
@endsection
