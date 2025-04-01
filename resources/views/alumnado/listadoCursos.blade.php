@extends('layouts.app')

@section('content')
    <div class="container">
        <form class="form-inline d-flex justify-content-center" method="GET" action="{{ route('alumnos.buscar') }}">
            <h4>Buscar alumno:</h4>
            <div class="form-group mx-sm-3 mb-2">
                <label for="nombre" class="sr-only">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="{{ request('nombre') }}">
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label for="apellido1" class="sr-only">Apellido 1:</label>
                <input type="text" class="form-control" id="apellido1" name="apellido1" placeholder="Apellido 1" value="{{ request('apellido1') }}">
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label for="apellido2" class="sr-only">Apellido 2:</label>
                <input type="text" class="form-control" id="apellido2" name="apellido2" placeholder="Apellido 2" value="{{ request('apellido2') }}">
            </div>
            <button type="submit" class="btn btn-primary mb-2">Buscar</button>
        </form>
        @foreach ($annos as $anno)
            <div class="card m-4">
                <div class="card-header text-center">{{ $anno->anno }}</div>
                <div class="card-body">
                    @foreach ($cursos as $curso)
                        <div class="card m-4">
                            <div class="card-header text-center">{{ $curso[0]['ciclo'] }}</div>
                            <div class="card-body d-flex justify-content-around flex-wrap">
                                @for ($i = 0; $i < count($curso); $i++)
                                    <div class="card m-2 text-center" style="width: 40%">
                                        <div class="card-header">
                                            {{ $curso[$i]['curso'] . 'º ' . $curso[$i]['grupo'] . ' ' . $curso[$i]['turno'] }}
                                        </div>
                                        <div class="card-body">
                                            <?php
                                            $alumnos = DB::table('matricula')
                                                ->where('anno_academico', '=', $anno->anno)
                                                ->where('curso_academico_id', '=', $curso[$i]['id'])
                                                ->count();
                                            ?>
                                            <code>{{ 'Hay ' . $alumnos . ' alumnos matriculados.' }}</code>
                                        </div>
                                        <div class="card-footer">
                                            <?php
                                            $anio = str_replace('/', '-', $anno->anno);
                                            ?>
                                            <a href="{{ route('alumnos.infoCurso', ['anno' => $anio, 'curso' => $curso[$i]['id']]) }}"
                                                class="btn btn-primary">Ver alumnos</a>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer"></div>
            </div>
        @endforeach
    </div>
@endsection
