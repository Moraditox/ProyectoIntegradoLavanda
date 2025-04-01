@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex flex-row justify-content-between align-items-center">
                        {{ __('Convocatorias') }}
                        <a href="{{ route('convocatoria.create') }}" class="btn btn-primary float-right">Crear
                            Convocatoria</a>
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
                        @foreach ($convocatorias as $anno_academico => $convocatoriasPorAnno)
                            <div class="card m-4  text-center">
                                <div class="card-header">
                                    {{ $anno_academico }}
                                </div>
                                <div class="card-body d-flex flex-row justify-content-around align-items-center">
                                @foreach ($convocatoriasPorAnno as $convocatoria)
    <div class="card @if ($convocatoria->estado == 'En proceso') border-success @elseif ($convocatoria->estado == 'Acabada') border-primary @endif">
                                            <div class="card-header">
                                                {{ $convocatoria->periodo }}
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">
                                                    <strong>Fecha de inicio:</strong>
                                                    {{ $convocatoria->fecha_inicio }}
                                                </p>
                                                <p class="card-text">
                                                    <strong>Fecha de fin:</strong>
                                                    {{ $convocatoria->fecha_fin }}
                                                </p>
                                                <p class="card-text">
                                                    <strong>Observaciones:</strong>
                                                    {{ $convocatoria->observaciones }}
                                                </p>
                                                <p class="card-text">
                                                    <strong>Estado:</strong>
                                                    {{ $convocatoria->estado }}
                                                </p>
                                            </div>
                                            <div class="card-footer">
                                                <a href="{{ route('convocatoria.show', $convocatoria->id) }}"
                                                    class="btn btn-primary">Ver</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="card-footer">

                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
