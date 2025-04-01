<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lavanda - {{ $empresa->nombre }} - Seguimiento - {{ $alumno->nombre }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('storage/images/favicon.ico') }}">
</head>

<body>
    <div class="text-center">
    <img src="{{ asset('storage/avatar/iesgrancapitan.png') }}" alt="Logo" style="max-width: 100px;">
        <h1>Lavanda</h1>
    </div>

    <div class="text-center">
        <h2>{{ $empresa->nombre }} bienvenido a la página de seguimiento del alumno
            {{ $alumno->nombre . ' ' . $alumno->apellido1 . ' ' . $alumno->apellido2 }}</h2>
        <code>Por favor rellene los campos siguientes:</code>
    </div>

    <div class="card card-default">
        <div class="card-body">
            <form method="POST"
               action="{{ route('guardarInformeEmpresaAlumno', ['token' => $empresa->token, 'empresa' => $empresa->id, 'alumno' => $alumno->id]) }}"

                role="form" enctype="multipart/form-data">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="correo"><strong>Correo:</strong> </label>
                            <input type="text" name="correo" id="correo"
                                class="form-control{{ $errors->has('correo') ? ' is-invalid' : '' }}"
                                placeholder="Introduzca tu dirección de correo electrónico"
                                value="{{ $empresa->email }}">
                            {!! $errors->first('correo', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <label for="empresa"><strong>Empresa: </strong></label>
                            <input type="text" name="empresa" id="empresa"
                                class="form-control{{ $errors->has('empresa') ? ' is-invalid' : '' }}"
                                placeholder="Introduzca el nombre de la empresa" value="{{ $empresa->nombre }}"
                                disabled>
                            {!! $errors->first('empresa', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group" hidden>
                            <label for="empresa_id">Id Empresa: </label>
                            <input type="text" name="empresa_id" id="empresa_id"
                                class="form-control{{ $errors->has('empresa_id') ? ' is-invalid' : '' }}"
                                placeholder="Introduzca el id de la empresa" value="{{ $empresa->id }}">
                            {!! $errors->first('empresa_id', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group" hidden>
                            <label for="ciclo">Ciclo: </label>
                            <input type="text" name="ciclo" id="ciclo"
                                class="form-control{{ $errors->has('ciclo') ? ' is-invalid' : '' }}"
                                placeholder="Introduzca el nombre del ciclo del alumno" value="{{ $ciclo }}">
                        </div>

                        <div class="form-group">
                            <label for="ciclo_disabled"><strong>Ciclo: </strong></label>
                            <input type="text" name="ciclo_disabled" id="ciclo_disabled"
                                class="form-control{{ $errors->has('ciclo') ? ' is-invalid' : '' }}"
                                placeholder="Introduzca el nombre del ciclo del alumno" value="{{ $ciclo }}"
                                disabled>
                        </div>

                        <div class="form-group">
                            <label for="alumno"><strong>Alumno:</strong> </label>
                            <input type="text" name="alumno" id="alumno"
                                class="form-control{{ $errors->has('alumno') ? ' is-invalid' : '' }}"
                                placeholder="Introduzca el nombre del alumno"
                                value="{{ $alumno->nombre . ' ' . $alumno->apellido1 . ' ' . $alumno->apellido2 }}"
                                disabled>
                        </div>

                        <div class="form-group" hidden>
                            <label for="alumnado_id">Id Alumno: </label>
                            <input type="text" name="alumnado_id" id="alumnado_id"
                                class="form-control{{ $errors->has('alumnado_id') ? ' is-invalid' : '' }}"
                                placeholder="Introduzca el id del alumno"
                                value="{{ $alumno->id }}">
                        </div>

                        <div>
                            <p><strong>Competencias profesionales.</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="competencias_profesionales"
                                    id="competencias_profesionales_nada_satisfecho" value="Nada satisfecho">
                                <label class="form-check-label" for="competencias_profesionales_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="competencias_profesionales"
                                    id="competencias_profesionales_poco_satisfecho" value="Poco satisfecho">
                                <label class="form-check-label" for="competencias_profesionales_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="competencias_profesionales"
                                    id="competencias_profesionales_satisfecho" value="Satisfecho">
                                <label class="form-check-label" for="competencias_profesionales_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="competencias_profesionales"
                                    id="competencias_profesionales_muy_satisfecho" value="Muy satisfecho">
                                <label class="form-check-label" for="competencias_profesionales_muy_satisfecho">
                                    Muy satisfecho
                                </label>
                            </div>
                        </div>

                        <div>
                            <p><strong>Competencias organizativas.</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="competencias_organizativas"
                                    id="competencias_organizativas_nada_satisfecho" value="Nada satisfecho">
                                <label class="form-check-label" for="competencias_organizativas_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="competencias_organizativas"
                                    id="competencias_organizativas_poco_satisfecho" value="Poco satisfecho">
                                <label class="form-check-label" for="competencias_organizativas_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="competencias_organizativas"
                                    id="competencias_organizativas_satisfecho" value="Satisfecho">
                                <label class="form-check-label" for="competencias_organizativas_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="competencias_organizativas"
                                    id="competencias_organizativas_muy_satisfecho" value="Muy satisfecho">
                                <label class="form-check-label" for="competencias_organizativas_muy_satisfecho">
                                    Muy satisfecho
                                </label>
                            </div>
                        </div>

                        <div>
                            <p><strong>Competencias relacionales.</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="competencias_relacionales"
                                    id="competencias_relacionales_nada_satisfecho" value="Nada satisfecho">
                                <label class="form-check-label" for="competencias_relacionales_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="competencias_relacionales"
                                    id="competencias_relacionales_poco_satisfecho" value="Poco satisfecho">
                                <label class="form-check-label" for="competencias_relacionales_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="competencias_relacionales"
                                    id="competencias_relacionales_satisfecho" value="Satisfecho">
                                <label class="form-check-label" for="competencias_relacionales_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="competencias_relacionales"
                                    id="competencias_relacionales_muy_satisfecho" value="Muy satisfecho">
                                <label class="form-check-label" for="competencias_relacionales_muy_satisfecho">
                                    Muy satisfecho
                                </label>
                            </div>
                        </div>

                        <div>
                            <p><strong>Capacidad de respuesta a las contingencias.</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="capacidad_de_respuesta_a_las_contingencias"
                                    id="capacidad_de_respuesta_a_las_contingencias_nada_satisfecho"
                                    value="Nada satisfecho">
                                <label class="form-check-label"
                                    for="capacidad_de_respuesta_a_las_contingencias_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="capacidad_de_respuesta_a_las_contingencias"
                                    id="capacidad_de_respuesta_a_las_contingencias_poco_satisfecho"
                                    value="Poco satisfecho">
                                <label class="form-check-label"
                                    for="capacidad_de_respuesta_a_las_contingencias_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="capacidad_de_respuesta_a_las_contingencias"
                                    id="capacidad_de_respuesta_a_las_contingencias_satisfecho" value="Satisfecho">
                                <label class="form-check-label"
                                    for="capacidad_de_respuesta_a_las_contingencias_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="capacidad_de_respuesta_a_las_contingencias"
                                    id="capacidad_de_respuesta_a_las_contingencias_muy_satisfecho"
                                    value="Muy satisfecho">
                                <label class="form-check-label"
                                    for="capacidad_de_respuesta_a_las_contingencias_muy_satisfecho">
                                    Muy satisfecho
                                </label>
                            </div>
                        </div>

                        <div>
                            <p><strong>Capacidad de aprendizaje.</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="capacidad_de_aprendizaje"
                                    id="capacidad_de_aprendizaje_nada_satisfecho" value="Nada satisfecho">
                                <label class="form-check-label" for="capacidad_de_aprendizaje_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="capacidad_de_aprendizaje"
                                    id="capacidad_de_aprendizaje_poco_satisfecho" value="Poco satisfecho">
                                <label class="form-check-label" for="capacidad_de_aprendizaje_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="capacidad_de_aprendizaje"
                                    id="capacidad_de_aprendizaje_satisfecho" value="Satisfecho">
                                <label class="form-check-label" for="capacidad_de_aprendizaje_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="capacidad_de_aprendizaje"
                                    id="capacidad_de_aprendizaje_muy_satisfecho" value="Muy satisfecho">
                                <label class="form-check-label" for="capacidad_de_aprendizaje_muy_satisfecho">
                                    Muy satisfecho
                                </label>
                            </div>
                        </div>

                        <div>
                            <p><strong>Cumplimiento de las normas.</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cumplimiento_de_las_normas"
                                    id="cumplimiento_de_las_normas_nada_satisfecho" value="Nada satisfecho">
                                <label class="form-check-label" for="cumplimiento_de_las_normas_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cumplimiento_de_las_normas"
                                    id="cumplimiento_de_las_normas_poco_satisfecho" value="Poco satisfecho">
                                <label class="form-check-label" for="cumplimiento_de_las_normas_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cumplimiento_de_las_normas"
                                    id="cumplimiento_de_las_normas_satisfecho" value="Satisfecho">
                                <label class="form-check-label" for="cumplimiento_de_las_normas_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cumplimiento_de_las_normas"
                                    id="cumplimiento_de_las_normas_muy_satisfecho" value="Muy satisfecho">
                                <label class="form-check-label" for="cumplimiento_de_las_normas_muy_satisfecho">
                                    Muy satisfecho
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="actividades_formativas_y_tareas_realizadas_en_la_empresa"><strong>Actividades
                                formativas y tareas realizadas en la empresa.</strong></label>
                            <textarea class="form-control" name="actividades_formativas_y_tareas_realizadas_en_la_empresa"
                                id="actividades_formativas_y_tareas_realizadas_en_la_empresa" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="observaciones_sobre_competencias_profesionales"><strong>Observaciones sobre
                                competencias profesionales.</strong>
                            </label>
                            <textarea class="form-control" name="observaciones_sobre_competencias_profesionales"
                                id="observaciones_sobre_competencias_profesionales" rows="3"></textarea>
                        </div>


                        <div class="form-group">
                            <label for="observaciones_sobre_competencias_personales_y_sociales"><strong>Observaciones sobre
                                competencias personales y sociales.</strong>
                            </label>
                            <textarea class="form-control" name="observaciones_sobre_competencias_personales_y_sociales"
                                id="observaciones_sobre_competencias_personales_y_sociales" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="sugerencias_generales_de_mejora"><strong>Sugerencias generales de mejora.</strong></label>
                            <textarea class="form-control" name="sugerencias_generales_de_mejora" id="sugerencias_generales_de_mejora"
                                rows="3"></textarea>
                        </div>

                        <div>
                            <p><strong>Satisfacción global con el alumno/a</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="satisfacción_global_con_el_alumno"
                                    id="satisfacción_global_con_el_alumno_nada_satisfecho" value="Nada satisfecho">
                                <label class="form-check-label"
                                    for="satisfacción_global_con_el_alumno_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="satisfacción_global_con_el_alumno"
                                    id="satisfacción_global_con_el_alumno_poco_satisfecho" value="Poco satisfecho">
                                <label class="form-check-label"
                                    for="satisfacción_global_con_el_alumno_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="satisfacción_global_con_el_alumno"
                                    id="satisfacción_global_con_el_alumno_satisfecho" value="Satisfecho">
                                <label class="form-check-label" for="satisfacción_global_con_el_alumno_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="satisfacción_global_con_el_alumno" id="satisfacción_global_con_el_alumno"
                                    value="Muy satisfecho">
                                <label class="form-check-label"
                                    for="satisfacción_global_con_el_alumno_muy_satisfecho">
                                    Muy satisfecho
                                </label>
                            </div>
                        </div>

                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="box-footer mt-2">
                        <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
                        <a href="/lavanda/public/" class="btn btn-danger">{{ __('Cancelar') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
