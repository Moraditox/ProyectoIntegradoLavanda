@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <form action="{{ route('empresa.listadoEmpresas') }}" method="GET" class="form-inline my-2">
                <div class="input-group w-100">
                    <input class="form-control mr-sm-2" type="search" name="search" placeholder="{{ __('Buscar') }}"
                        aria-label="{{ __('Buscar') }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">{{ __('Buscar') }}</button>
                    </div>
                </div>
            </form>
            <div class="row">
                @foreach ($empresas as $empresa)
                    <div style="margin-bottom: 20px" class="col-md-3">
                        <div style="height:100%;" class="card mb-4 text-center">
                            <div class="card-header">
                                {{ $empresa['nombre'] }}
                            </div>
                            <div class="card-body">
                                <img src="{{ asset('storage/logos/' . $empresa['logo']) }}" alt="Logo de la empresa"
                                    width="130">
                                <p><strong>Descripción:</strong> {{ $empresa['descripcion'] }}</p>
                                <p><strong>CIF:</strong> {{ $empresa['cif'] }}</p>
                                <p><strong>Dirección:</strong> {{ $empresa['direccion'] }}</p>
                                <p><strong>Representante Legal:</strong> {{ $empresa['representante_legal'] }}</p>
                                <p><strong>Email:</strong> <a
                                        href="mailto:{{ $empresa['email'] }}">{{ $empresa['email'] }}</a>
                                </p>
                                <p><strong>Móvil:</strong> {{ $empresa['movil'] }}</p>
                                <p><strong>NIF Representante Legal:</strong> {{ $empresa['nif_representante_legal'] }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('empresa.convocatorias', $empresa['nombre']) }}"
                                    class="btn btn-primary">{{ __('Ver convocatorias') }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
