@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card mt-3">
                <div class="card-header text-center">
                    Detalles del Alumno
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if ($alumno['imagen'] && file_exists(storage_path('app/public/alumnado/perfil/' .
                        $alumno['imagen'])))
                        <img src="{{ asset('storage/alumnado/perfil/' . $alumno['imagen']) }}" alt="Imagen del Alumno"
                            class="rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                        @else
                        <img src="{{ asset('storage/avatar/avatar2.png') }}" alt="Avatar predeterminado"
                            class="rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
                        @endif
                    </div>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td>Nombre</td>
                                <td>{{ $alumno['nombre'] }}</td>
                            </tr>
                            <tr>
                                <td>Apellido 1</td>
                                <td>{{ $alumno['apellido1'] }}</td>
                            </tr>
                            <tr>
                                <td>Apellido 2</td>
                                <td>{{ $alumno['apellido2'] }}</td>
                            </tr>
                            <tr>
                                <td>NIE</td>
                                <td>{{ $alumno['nie'] }}</td>
                            </tr>
                            <tr>
                                <td>Email Corporativo</td>
                                <td><a href="mailto:{{ $alumno['email_corporativo'] }}">{{ $alumno['email_corporativo']
                                        }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Email Personal</td>
                                <td><a href="mailto:{{ $alumno['email_personal'] }}">{{ $alumno['email_personal'] }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td>DNI</td>
                                <td>{{ $alumno['dni'] }}</td>
                            </tr>
                            <tr>
                                <td>Móvil</td>
                                <td>{{ $alumno['movil'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card m-4">
        <div class="card-header text-center">
            Listado de Cursos
        </div>
        <div class="card-body text-center">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Año</th>
                        <th>Curso</th>
                        <th>Convocatorias</th>
                        <th>Ver Informes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($matriculas as $matricula)
                    <tr>
                        <td>{{ str_replace('/', ' - ', $matricula['anno_academico']) }}</td>
                        <td>
                            @if ($matricula->curso_academico)
                            {{ $matricula->curso_academico->curso . 'º ' . $matricula->curso_academico->grupo . ' ' .
                            $matricula->curso_academico->ciclo . ' ' . $matricula->curso_academico->turno }}
                            @else
                            No hay datos de curso académico
                            @endif
                        </td>

                        </td>
                        <td>
                            @foreach ($asignaciones as $asignacion)
                            <a href="{{ route('convocatoria.show', $asignacion->convocatorias->id) }}"
                                class="btn btn-sm btn-primary">{{ $asignacion->convocatorias->periodo }}</a>
                            @endforeach
                        </td>
                        <td>
                        @if (Storage::disk('public')->exists("informes/fichas_semanales/ficha_semanal_{$alumno->id}.pdf"))
                        <a href="{{ route('ver_ficha_semanal', $alumno->id) }}" class="btn btn-sm btn-primary">Ver Ficha Semanal</a>
                        @else
                        <button class="btn btn-sm btn-secondary" disabled>Ver Ficha Semanal</button>
                        @endif
                        
                        @if (Storage::disk('public')->exists("informes/alumnado/informe_alumnado_{$alumno->id}.pdf"))
                        <a href="{{ route('ver_informe_alumnado', ['id' => $alumno->id]) }}" class="btn btn-sm btn-primary">Ver Informe
                            Alumnado</a>
                        @else
                        <button class="btn btn-sm btn-secondary" disabled>Ver Informe Alumnado</button>
                        @endif
                        
                        @if (Storage::disk('public')->exists("informes/profesorado/informe_profesorado_{$alumno->id}.pdf"))
                        <a href="{{ route('ver_informe_profesorado', ['id' => $alumno->id]) }}" class="btn btn-sm btn-primary">Ver Informe
                            Profesorado</a>
                        @else
                        <button class="btn btn-sm btn-secondary" disabled>Ver Informe Profesorado</button>
                        @endif

                        </td>


                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer"></div>
    </div>
</div>
@endsection