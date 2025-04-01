@extends('layouts.app')

@section('template_title')
    {{ $convocatoria->observaciones ?? __('Show Convocatoria') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"
                        style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Información de la asignación') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary"
                                href="{{ route('convocatoria.show', $convocatoria->id) }}">{{ __('Volver') }}</a>
                        </div>
                    </div>
                    @if (session('success'))
    <div class="alert alert-success m-2">
        <p>{{ session('success') }}</p>
    </div>
@endif

                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger m-2">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="card">
                            <div class="card-header text-center">
                                <strong>{{ $curso->curso . 'º ' . $curso->grupo . ' ' . $curso->ciclo . ' ' . $curso->turno }}</strong>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <form action="{{ route('asignaciones.store', $convocatoria->id) }}" method="POST">
                                        @csrf
                                        <div class="card m-2">
                                            <div class="card-header">
                                                <strong>Añadir nuevas asignaciones</strong>
                                            </div>
                                            <div class="card-body">
                                                <div id="section1" class="form-section visible">
                                                    <code>No se mostrarán los alumnos que ya tengan una
                                                        asignación.</code><br>
                                                    <div class="form-group">
                                                        {{ Form::label('alumnos', 'Lista de alumnos') }}
                                                        <select class="form-control select2" name="alumnos[]" multiple
                                                            style="width: 100%">
                                                            <option value="" disabled>Selecciona los alumnos</option>
                                                            @foreach ($alumnos as $alumno)
                                                                <option value="{{ $alumno->id }}">
                                                                    {{ $alumno->nombre . ' ' . $alumno->apellido1 . ' ' . $alumno->apellido2 }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="section2" class="form-section" style="display: none;">
                                                    <div class="form-group">
                                                        {{ Form::label('profesores', 'Lista de profesores') }}
                                                        <select class="form-control select2" name="profesores"
                                                            style="width: 100%">
                                                            <option value="" selected disabled>Selecciona al profesor
                                                            </option>
                                                            @foreach ($profesores as $profesor)
                                                                <option value="{{ $profesor->id }}">
                                                                    {{ $profesor->nombre . ' ' . $profesor->apellido1 . ' ' . $profesor->apellido2 }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="section3" class="form-section" style="display: none;">
                                                    <div class="form-group">
                                                        {{ Form::label('trabajador_id', 'Lista de trabajadores') }}
                                                        <select class="form-control select2" name="trabajador_id"
                                                            style="width: 100%">
                                                            <option value="" selected disabled>Selecciona al
                                                                trabajador</option>
                                                            @foreach ($trabajadores as $empresaId => $trabajadoresEmpresa)
                                                                @php
                                                                    $empresa = \App\Models\Empresa::find($empresaId);
                                                                @endphp
                                                                <optgroup label="Empresa: {{ $empresa->nombre }}">
                                                                    @foreach ($trabajadoresEmpresa as $trabajador)
                                                                        <option value="{{ $trabajador->id }}">
                                                                            {{ $empresa->nombre . ' - ' . $trabajador->nombre . ' ' . $trabajador->apellido1 . ' ' . $trabajador->apellido2 }}
                                                                        </option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="section4" class="form-section" style="display: none;">
                                                    <code>¿Estás seguro de que quieres añadir estas asignaciones?</code><br>
                                                    <button type="button" onclick="previousSection()"
                                                        class="btn btn-secondary">Anterior</button>
                                                    <button type="submit" class="btn btn-danger">Añadir</button>
                                                </div>
                                                <div id="botones">
                                                    <button type="button" onclick="previousSection()"
                                                        class="btn btn-secondary">Anterior</button>
                                                    <button type="button" onclick="nextSection()"
                                                        class="btn btn-primary">Siguiente</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    @empty($asignaciones)
                                        <div class="alert alert-danger" role="alert">
                                            {{ __('No hay asignaciones para esta convocatoria') }}
                                        </div>
                                    @else
                                        <table class="table table-striped table-hover">
                                            <thead class="thead">
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Convocatoria</th>
                                                    <th>Alumno</th>
                                                    <th>Profesor</th>
                                                    <th>Empresa</th>
                                                    <th>Trabajador</th>
                                                    <th>Formulario Seguimiento</th>
                                                    <th>Informe</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($asignaciones as $asignacion)
                                                    <tr>
                                                        <td>{{ $asignacion->id }}</td>
                                                        <td>{{ $asignacion->convocatorias->periodo . ' ' . $asignacion->convocatorias->anno_academico }}
                                                        </td>
                                                        <td>{{ $asignacion->alumnado->nombre . ' ' . $asignacion->alumnado->apellido1 . ' ' . $asignacion->alumnado->apellido2 }}
                                                        </td>
                                                        <td>{{ $asignacion->profesores->nombre . ' ' . $asignacion->profesores->apellido1 . ' ' . $asignacion->profesores->apellido2 }}
                                                        </td>
                                                        <td><a
                                                                href="{{ route('empresas.show', $asignacion->trabajadores->empresas->id) }}">{{ $asignacion->trabajadores->empresas->nombre }}</a>
                                                        </td>
                                                        <td>{{ $asignacion->trabajadores->nombre . ' ' . $asignacion->trabajadores->apellido1 . ' ' . $asignacion->trabajadores->apellido2 }}
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-success btn-sm"
                                                                data-toggle="modal"
                                                                data-target="#correoSeguimientoModal{{ $asignacion->alumnado->id }}">
                                                                <i class="fa fa-fw fa-trash"></i> {{ __('Enviar') }}
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-success btn-sm"
                                                                data-toggle="modal"
                                                                data-target="#correoInformeModal{{ $asignacion->alumnado->id }}">
                                                                <i class="fa fa-fw fa-trash"></i> {{ __('Enviar') }}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <!-- Modal de confirmación correo informe -->
                                                    <div class="modal fade"
                                                        id="correoInformeModal{{ $asignacion->alumnado->id }}" tabindex="-1"
                                                        role="dialog"
                                                        aria-labelledby="correoInformeModalLabel{{ $asignacion->alumnado->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="correoInformeModalLabel{{ $asignacion->alumnado->id }}">
                                                                        Confirmar
                                                                        envío informe</h5>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body" style="text-align: center">
                                                                    ¿Está seguro de que desea enviar el informe a
                                                                    <code>{{ $asignacion->alumnado->nombre . ' ' . $asignacion->alumnado->apellido1 . ' ' . $asignacion->alumnado->apellido2 }}</code>?
                                                                    <br>
                                                                    @if ($asignacion->alumnado->imagen && file_exists(public_path('storage/alumnado/perfil/' . $asignacion->alumnado->imagen)))
                                                                    <img src="{{ asset('storage/alumnado/perfil/' . $asignacion->alumnado->imagen) }}" alt="Foto Perfil" style="max-width: 50%;">
                                                                    @else
                                                                   <img src="{{ asset('storage/avatar/avatar2.png') }}" alt="Avatar Predeterminado" style="max-width: 20%;">
                                                                    @endif




                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Cancelar</button>
                                                                    <a class="btn btn-danger"
                                                                        href="{{ route('mail.enviarInforme', [$asignacion->alumnado->token, 'Alumno', $asignacion->alumnado->email_corporativo]) }}">
                                                                        <i class="fa fa-fw fa-envelope"></i>
                                                                        {{ __('Enviar') }}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Modal de confirmación correo seguimiento -->
                                                    <div class="modal fade"
                                                        id="correoSeguimientoModal{{ $asignacion->alumnado->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="correoSeguimientoModalLabel{{ $asignacion->alumnado->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="correoSeguimientoModalLabel{{ $asignacion->alumnado->id }}">
                                                                        Confirmar
                                                                        envío formulario seguimiento</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body" style="text-align: center">
                                                                    ¿Está seguro de que desea enviar el formulario de
                                                                    seguimiento a
                                                                    <code>{{ $asignacion->alumnado->nombre . ' ' . $asignacion->alumnado->apellido1 . ' ' . $asignacion->alumnado->apellido2 }}</code>?
                                                                    <br>
                                                                    @if ($asignacion->alumnado->imagen && file_exists(public_path('storage/alumnado/perfil/' . $asignacion->alumnado->imagen)))
                                                                    <img src="{{ asset('storage/alumnado/perfil/' . $asignacion->alumnado->imagen) }}" alt="Foto Perfil" style="max-width: 50%;">
                                                                    @else
                                                                   <img src="{{ asset('storage/avatar/avatar2.png') }}" alt="Avatar Predeterminado" style="max-width: 20%;">
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Cancelar</button>
                                                                    <a class="btn btn-danger"
                                                                        href="{{ route('mail.enviarSeguimiento', [$asignacion->alumnado->token, 'Alumno', $asignacion->alumnado->email_corporativo]) }}">
                                                                        <i class="fa fa-fw fa-envelope"></i>
                                                                        {{ __('Enviar') }}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endempty
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        var currentSectionIndex = 0;
        var formSections = document.querySelectorAll('.form-section');

        function previousSection() {
            if (currentSectionIndex > 0) {
                formSections[currentSectionIndex].classList.remove('visible');
                formSections[currentSectionIndex].style.display = 'none';
                currentSectionIndex--;
                formSections[currentSectionIndex].classList.add('visible');
                formSections[currentSectionIndex].style.display = 'block';
                document.getElementById('botones').style.display = 'block';
            }
        }

        function nextSection() {
            if (currentSectionIndex < formSections.length - 1) {
                formSections[currentSectionIndex].classList.remove('visible');
                formSections[currentSectionIndex].style.display = 'none';
                currentSectionIndex++;
                formSections[currentSectionIndex].classList.add('visible');
                formSections[currentSectionIndex].style.display = 'block';
            }
            if (currentSectionIndex == formSections.length - 1) {
                document.getElementById('botones').style.display = 'none';
            }
        }
        $(document).ready(function() {
            $('.select2').select2({
                language: {
                    noResults: function() {
                        return "No hay resultados";
                    }
                }
            });
        });
    </script>
@endsection
