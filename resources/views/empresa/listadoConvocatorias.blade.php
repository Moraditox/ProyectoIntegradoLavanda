@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                Convocatorias de la empresa: {{ $empresa->nombre }}
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/logos/' . $empresa->logo) }}" alt="Imagen de la empresa"
                        style="width: 200px;height:80px">
                </div>
                @php
                    $convocatoriasPorAnno = [];
                @endphp

                @foreach ($convocatoria_empresa as $conv_emp)
                    @php
                        $anno = $conv_emp->convocatorias->anno_academico;
                        $convocatoriasPorAnno[$anno][] = $conv_emp->convocatorias;
                    @endphp
                @endforeach

                @foreach ($convocatoriasPorAnno as $anno => $convocatorias)
                    <div class="card m-2">
                        <div class="card-header text-center">{{ $anno }}</div>
                        <div class="card-body">
                            @foreach ($convocatorias as $convocatoria)
                                <div class="card m-2">
                                    <div class="card-header text-center">{{ $convocatoria->periodo }}</div>
                                    <div class="card-body text-center">
                                        @foreach ($convocatoria->convocatoria_cursos as $curso)
                                            <a href="{{ route('asignaciones.show', ['convocatoria_id' => $convocatoria->id, 'curso_id' => $curso->curso_academico->id]) }}"
                                                class="btn btn-primary">{{ $curso->curso_academico->curso . 'º ' . $curso->curso_academico->grupo . ' ' . $curso->curso_academico->ciclo . ' ' . $curso->curso_academico->turno }}</a>
                                        @endforeach
                                    </div>
                                    <div class="card-footer"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="card-footer"></div>
        </div>
    </div>
@endsection
