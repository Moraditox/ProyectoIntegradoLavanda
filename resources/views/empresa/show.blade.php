@extends('layouts.app')

@section('template_title')
{{ $empresa->name ?? __('Show Empresa') }}
@endsection

@section('content')

<div class="card mt-4">
    <div class="card-header"
        style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
        <div class="float-left">
            @if ($empresa->logo)
            <img src="{{ asset('storage/logos/' . $empresa->logo) }}" alt="{{ $empresa->name }} Logo" height="50">
            @endif
            <span class="card-title">{{ $empresa->nombre }}</span>
        </div>
    </div>
    <div style="padding:20px">
        <h4>Valoración global por parte del alumnado: {{ $mediaInformesAlumnado }}</h4><br>
        <h4>Valoración global por parte del profesorado: {{ $mediaInformesProfesorado }}</h4>
    </div>
    <ul class="nav nav-tabs">
    <li class="nav-item">
            <a class="nav-link" id="informacion-empresa-tab" data-toggle="tab" href="#informacion-empresa">Información
                de la Empresa</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="empleados-tab" data-toggle="tab" href="#empleados">Empleados</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" id="fct-tab" data-toggle="tab" href="#fct">DUAL</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="actuacion-empresa-tab" data-toggle="tab" href="#actuacion-empresa">Actuaciones</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="evaluacion-tab" data-toggle="tab" href="#evaluacion">Evaluación</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade" id="informacion-empresa">
            <div class="card-body">
                <div class="form-group">
                    <strong>Id:</strong>
                    {{ $empresa->id }}
                </div>
                <div class="form-group">
                    <strong>Nombre:</strong>
                    {{ $empresa->nombre }}
                </div>
                <div class="form-group">
                    <strong>Descripcion:</strong>
                    {{ $empresa->descripcion }}
                </div>
                <div class="form-group">
                    <strong>Cif:</strong>
                    {{ $empresa->cif }}
                </div>
                <div class="form-group">
                    <strong>Direccion:</strong>
                    {{ $empresa->direccion }}
                </div>
                <div class="form-group">
                    <strong>Página Web:</strong>
                    <a href="{{ $empresa->web }}" target="_blank">{{ $empresa->web }}</a>
                </div>

                <div class="form-group">
                    <strong>Localidad:</strong>
                    {{ $empresa->localidad }}
                </div>
                <div class="form-group">
                    <strong>Persona contacto:</strong>
                    {{ $empresa->persona_contacto }}
                </div>
                <div class="form-group">
                    <strong>Correo contacto:</strong>
                    {{ $empresa->correo_contacto }}
                </div>
                <div class="form-group">
                    <strong>Teléfono contacto:</strong>
                    {{ $empresa->telefono_contacto }}
                </div>
                <div class="form-group">
                    <strong>Represetante legal:</strong>
                    {{ $empresa->representante_legal }}
                </div>
                <div class="form-group">
                    <strong>Nif Representante Legal:</strong>
                    {{ $empresa->nif_representante_legal }}
                </div>
                <div class="form-group">
                    <strong>Nif Tutor Laboral:</strong>
                    {{ $empresa->nif_tutor_laboral }}
                </div>
                <div class="form-group">
                    <strong>Email:</strong>
                    {{ $empresa->email }}
                </div>
                <div class="form-group">
                    <strong>Movil:</strong>
                    {{ $empresa->movil }}
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="empleados">
            <div class="card-body">
                <div class="table-responsive">
                    <div class="alert alert-info"
                        style="display: flex; justify-content: space-between; align-items: center">
                        <span>Puede añadir empleados a esta empresa haciendo clic en el botón a continuación:</span>
                        <a href="{{ route('trabajadores') }}" class="btn btn-warning">Añadir trabajadores</a>
                    </div>
                    <table class="table table-striped table-hover">
                        <thead class="thead">
                            <tr>
                                <th>Nombre</th>
                                <th>Rol</th>
                                <th>Email de Contacto</th>
                                <th>Teléfono de Contacto</th>
                                <th>NIF</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($empleados as $empleado)
                            <tr>
                                <td>{{ $empleado->nombre }}</td>
                                <td>{{ $empleado->rol }}</td>
                                <td>{{ $empleado->email }}</td>
                                <td>{{ $empleado->movil }}</td>
                                <td>{{ $empleado->nif }}</td>
                                <td>
                                    <!-- Aquí puedes agregar los botones de Acciones como ver, editar y eliminar -->
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show active" id="fct">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Nombre</th>
                                <th>Profesor</th>
                                <th>Curso</th>
                                <th>Ciclo</th>
                                <th>Convocatoria</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alumnosFct as $alumno)
                            <tr>
                                <td>
                                    <img width="50px"
                                        src="{{ $alumno->foto ? asset($alumno->foto) : asset('storage/avatar/avatar2.png') }}"
                                        alt="Foto de {{ $alumno->nombre }}">
                                </td>
                                <td>{{ $alumno->nombre }} {{ $alumno->apellido1 }}</td>
                                <td>
                                    @if ($alumno->profesor)
                                    {{ $alumno->profesor->nombre }} {{ $alumno->profesor->apellido }}
                                    @else
                                    No hay datos del profesor
                                    @endif
                                </td>
                                <td>
                                    @if ($alumno->matricula && $alumno->matricula->curso_academico)
                                    {{ $alumno->matricula->curso_academico->curso . 'º ' .
                                    $alumno->matricula->curso_academico->grupo }}
                                    @else
                                    No hay datos de curso académico
                                    @endif
                                </td>
                                <td>
                                    @if ($alumno->matricula && $alumno->matricula->curso_academico)
                                    {{ $alumno->matricula->curso_academico->ciclo }}
                                    @else
                                    No hay datos de curso académico
                                    @endif
                                </td>
                                <td>
                                    @foreach($alumno->asignacionesDetalles as $asignacion)
                                    @if ($asignacion->convocatorias)
                                    {{ $asignacion->convocatorias->anno_academico }}
                                    @else
                                    No hay datos de convocatoria
                                    @endif
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div style="padding:20px" class="tab-pane fade" id="evaluacion">
            <h3>Media de evaluación de los informes de los alumnos sobre la empresa</h3>
            <p><strong>Posibilidades Formativas:</strong> {{ $mediaPosibilidadesFormativasAlumnado }}</p>
            <p><strong>Cumplimiento Programa:</strong> {{ $mediaCumplimientoProgramaAlumnado }}</p>
            <p><strong>Seguimiento Tutor Trabajo:</strong> {{ $mediaSeguimientoTutorTrabajoAlumnado }}</p>
            <p><strong>Seguimiento Profesor:</strong> {{ $mediaSeguimientoProfesorAlumnado }}</p>
            <p><strong>Posibilidades Laborales:</strong> {{ $mediaPosibilidadesLaboralesAlumnado }}</p>
            <p><strong>Adecuación Formación:</strong> {{ $mediaAdecuacionFormacionAlumnado }}</p>
            <p><strong>Nivel Satisfacción:</strong> {{ $mediaNivelSatisfaccionAlumnado }}</p>
            <p><strong>Valoración General:</strong> {{ $mediaValoracionGeneralAlumnado }}</p>
            <h3>Media de evaluación de los informes del profesorado sobre la empresa</h3>
            <p><strong>Posibilidades Formativas:</strong> {{ $mediaPosibilidadesFormativasProfesorado }}</p>
            <p><strong>Cumplimiento Programa:</strong> {{ $mediaCumplimientoProgramaProfesorado }}</p>
            <p><strong>Seguimiento Tutor Trabajo:</strong> {{ $mediaSeguimientoTutorTrabajoProfesorado }}</p>
            <p><strong>Seguimiento Profesor:</strong> {{ $mediaSeguimientoProfesorProfesorado }}</p>
            <p><strong>Posibilidades Laborales:</strong> {{ $mediaPosibilidadesLaboralesProfesorado }}</p>
            <p><strong>Adecuación Formación:</strong> {{ $mediaAdecuacionFormacionProfesorado }}</p>
            <p><strong>Nivel Satisfacción:</strong> {{ $mediaNivelSatisfaccionProfesorado }}</p>
            <p><strong>Valoración General:</strong> {{ $mediaValoracionGeneralProfesorado }}</p>




        </div>

        <div class="tab-pane fade" id="actuacion-empresa">
            <div class="card-body">
            <div class="table-responsive">
                <div class="alert alert-info"
                style="display: flex; justify-content: space-between; align-items: center">
                <span>Puede añadir actuaciones a esta empresa haciendo clic en el botón a continuación:</span>
                <a href="{{ route('actuaciones-empresa.create', ['empresa_id' => $empresa->id]) }}" class="btn btn-warning">Añadir actuación</a>
                </div>
                @if ( isset($actuaciones) && $actuaciones->isNotEmpty())
                <table class="table table-striped table-hover">
                <thead class="thead">
                    <tr>
                    <th>Profesor Encargado</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($actuaciones as $actuacion)
                    <tr>
                    <td>
                        @if ($actuacion->profesor)
                        {{ $actuacion->profesor->nombre }} {{ $actuacion->profesor->apellido }}
                        @else
                        No hay datos del profesor
                        @endif
                    </td>
                    <td>{{ $actuacion->created_at }}</td>
                    <td>{{ $actuacion->descripcion }}</td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
                @endif
            </div>
            </div>
        </div>
    </div>

    @endsection