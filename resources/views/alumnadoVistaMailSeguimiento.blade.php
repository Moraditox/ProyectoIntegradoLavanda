<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lavanda - {{ $alumno->nombre }} - Seguimiento</title>
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
        <h2>{{ $alumno->nombre . ' ' . $alumno->apellido1 . ' ' . $alumno->apellido2 }} bienvenido a la página de
            seguimiento de tu FCT en la empresa {{ $empresa->nombre }}.</h2>
        <code>Por favor rellena los campos siguientes:</code>
    </div>

    <div class="card card-default">
        <div class="card-body">
            <form method="POST"
                action="{{ route('guardarInformeAlumnoEmpresa', ['token' => $alumno->token, 'empresa' => $empresa->id]) }}"
                role="form" enctype="multipart/form-data">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="correo"><strong>Correo:</strong> </label>
                            <input type="text" name="correo" id="correo"
                                class="form-control{{ $errors->has('correo') ? ' is-invalid' : '' }}"
                                placeholder="Introduzca tu dirección de correo electrónico"
                                value="{{ $alumno->email_corporativo }}">
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
                            <label for="ciclo"><strong>Ciclo:</strong></label>
                            <input type="text" name="ciclo" id="ciclo"
                                class="form-control{{ $errors->has('ciclo') ? ' is-invalid' : '' }}"
                                placeholder="Introduzca el nombre del ciclo del alumno" value="{{ $ciclo }}">
                        </div>

                        <div class="form-group">
                            <label for="ciclo_disabled">Ciclo: </label>
                            <input type="text" name="ciclo_disabled" id="ciclo_disabled"
                                class="form-control{{ $errors->has('ciclo') ? ' is-invalid' : '' }}"
                                placeholder="Introduzca el nombre del ciclo del alumno" value="{{ $ciclo }}"
                                disabled>
                        </div>

                        <div class="form-group">
                            <label for="alumno"><strong>Alumno: </strong></label>
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
                                placeholder="Introduzca el id del alumno" value="{{ $alumno->id }}">
                        </div>

                        <div class="form-group">
                            <label for="actividades_formativas_y_tareas_que_realiza_la_empresa"><strong>Actividades formativas y
                                tareas que realiza la empresa:</strong>
                            </label>
                            <input type="text" name="actividades_formativas_y_tareas_que_realiza_la_empresa"
                                id="actividades_formativas_y_tareas_que_realiza_la_empresa"
                                class="form-control{{ $errors->has('actividades_formativas_y_tareas_que_realiza_la_empresa') ? ' is-invalid' : '' }}"
                                placeholder="Introduzca las actividades formativas y tareas que realiza la empresa">
                        </div>

                        <div class="form-group">
                            <label for="actividades_y_tareas_que_estas_realizando_en_la_empresa"><strong>Actividades y tareas
                                que estas realizando en la empresa:</strong>
                            </label>
                            <input type="text" name="actividades_y_tareas_que_estas_realizando_en_la_empresa"
                                id="actividades_y_tareas_que_estas_realizando_en_la_empresa"
                                class="form-control{{ $errors->has('actividades_y_tareas_que_estas_realizando_en_la_empresa') ? ' is-invalid' : '' }}"
                                placeholder="Introduzca las actividades y tareas que estas realizando en la empresa:">
                        </div>

                        <div>
                            <p><strong>Posibilidades formativas que ofrece la empresa:</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="posibilidades_formativas_que_ofrece_la_empresa"
                                    id="posibilidades_formativas_que_ofrece_la_empresa_nada_satisfecho"
                                    value="Nada satisfecho">
                                <label class="form-check-label"
                                    for="posibilidades_formativas_que_ofrece_la_empresa_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="posibilidades_formativas_que_ofrece_la_empresa"
                                    id="posibilidades_formativas_que_ofrece_la_empresa_poco_satisfecho"
                                    value="Poco satisfecho">
                                <label class="form-check-label"
                                    for="posibilidades_formativas_que_ofrece_la_empresa_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="posibilidades_formativas_que_ofrece_la_empresa"
                                    id="posibilidades_formativas_que_ofrece_la_empresa_satisfecho" value="Satisfecho">
                                <label class="form-check-label"
                                    for="posibilidades_formativas_que_ofrece_la_empresa_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="posibilidades_formativas_que_ofrece_la_empresa"
                                    id="posibilidades_formativas_que_ofrece_la_empresa_muy_satisfecho"
                                    value="Muy satisfecho">
                                <label class="form-check-label"
                                    for="posibilidades_formativas_que_ofrece_la_empresa_muy_satisfecho">
                                    Muy satisfecho
                                </label>
                            </div>
                        </div>

                        <div>
                            <p><strong>Cumplimiento del programa formativo por parte de la empresa.</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="cumplimiento_del_programa_formativo_por_parte_de_la_empresa"
                                    id="competencias_organizativas_nada_satisfecho"
                                    value="Nada satisfecho">
                                <label class="form-check-label" for="competencias_organizativas_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="cumplimiento_del_programa_formativo_por_parte_de_la_empresa"
                                    id="competencias_organizativas_poco_satisfecho"
                                    value="Poco satisfecho">
                                <label class="form-check-label" for="competencias_organizativas_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="cumplimiento_del_programa_formativo_por_parte_de_la_empresa"
                                    id="competencias_organizativas_satisfecho"
                                    value="Satisfecho">
                                <label class="form-check-label" for="competencias_organizativas_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="cumplimiento_del_programa_formativo_por_parte_de_la_empresa"
                                    id="competencias_organizativas_muy_satisfecho"
                                    value="Muy satisfecho">
                                <label class="form-check-label" for="competencias_organizativas_muy_satisfecho">
                                    Muy satisfecho
                                </label>
                            </div>
                        </div>

                        <div>
                            <p><strong>Seguimiento realizado por el tutor/a del centro de trabajo.</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="seguimiento_realizado_por_el_tutor_del_centro_de_trabajo"
                                    id="seguimiento_realizado_por_el_tutor_del_centro_de_trabajo_nada_satisfecho"
                                    value="Nada satisfecho">
                                <label class="form-check-label"
                                    for="seguimiento_realizado_por_el_tutor_del_centro_de_trabajo_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="seguimiento_realizado_por_el_tutor_del_centro_de_trabajo"
                                    id="seguimiento_realizado_por_el_tutor_del_centro_de_trabajo_poco_satisfecho"
                                    value="Poco satisfecho">
                                <label class="form-check-label"
                                    for="seguimiento_realizado_por_el_tutor_del_centro_de_trabajo_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="seguimiento_realizado_por_el_tutor_del_centro_de_trabajo"
                                    id="seguimiento_realizado_por_el_tutor_del_centro_de_trabajo_satisfecho"
                                    value="Satisfecho">
                                <label class="form-check-label"
                                    for="seguimiento_realizado_por_el_tutor_del_centro_de_trabajo_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="seguimiento_realizado_por_el_tutor_del_centro_de_trabajo"
                                    id="seguimiento_realizado_por_el_tutor_del_centro_de_trabajo_muy_satisfecho"
                                    value="Muy satisfecho">
                                <label class="form-check-label"
                                    for="seguimiento_realizado_por_el_tutor_del_centro_de_trabajo_muy_satisfecho">
                                    Muy satisfecho
                                </label>
                            </div>
                        </div>

                        <div>
                            <p><strong>Seguimiento hecho por tu profesor/a.</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="seguimiento_hecho_por_tu_profesor"
                                    id="seguimiento_hecho_por_tu_profesor_nada_satisfecho" value="Nada satisfecho">
                                <label class="form-check-label"
                                    for="seguimiento_hecho_por_tu_profesor_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="seguimiento_hecho_por_tu_profesor"
                                    id="seguimiento_hecho_por_tu_profesor_poco_satisfecho" value="Poco satisfecho">
                                <label class="form-check-label"
                                    for="seguimiento_hecho_por_tu_profesor_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="seguimiento_hecho_por_tu_profesor"
                                    id="seguimiento_hecho_por_tu_profesor_satisfecho" value="Satisfecho">
                                <label class="form-check-label" for="seguimiento_hecho_por_tu_profesor_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="seguimiento_hecho_por_tu_profesor"
                                    id="seguimiento_hecho_por_tu_profesor_muy_satisfecho" value="Muy satisfecho">
                                <label class="form-check-label"
                                    for="seguimiento_hecho_por_tu_profesor_muy_satisfecho">
                                    Muy satisfecho
                                </label>
                            </div>
                        </div>

                        <div>
                            <p><strong>Adecuación de la formación recibida en el centro docente con las prácticas realizadas.</strong>
                            </p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="adecuacion__formacion_recibida_en_centro_docente_con_practicas"
                                    id="adecuacion__formacion_recibida_en_centro_docente_con_practicas_nada_satisfecho"
                                    value="Nada satisfecho">
                                <label class="form-check-label"
                                    for="adecuacion__formacion_recibida_en_centro_docente_con_practicas_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="adecuacion__formacion_recibida_en_centro_docente_con_practicas"
                                    id="adecuacion__formacion_recibida_en_centro_docente_con_practicas_poco_satisfecho"
                                    value="Poco satisfecho">
                                <label class="form-check-label"
                                    for="adecuacion__formacion_recibida_en_centro_docente_con_practicas_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="adecuacion__formacion_recibida_en_centro_docente_con_practicas"
                                    id="adecuacion__formacion_recibida_en_centro_docente_con_practicas_satisfecho"
                                    value="Satisfecho">
                                <label class="form-check-label"
                                    for="adecuacion__formacion_recibida_en_centro_docente_con_practicas_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="adecuacion__formacion_recibida_en_centro_docente_con_practicas"
                                    id="adecuacion__formacion_recibida_en_centro_docente_con_practicas_muy_satisfecho"
                                    value="Muy satisfecho">
                                <label class="form-check-label"
                                    for="adecuacion__formacion_recibida_en_centro_docente_con_practicas_muy_satisfecho">
                                    Muy satisfecho
                                </label>
                            </div>
                        </div>

                        <div>
                            <p><strong>Integración en el entorno laboral.</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="integracion_en_el_entorno_laboral"
                                    id="integracion_en_el_entorno_laboral_nada_satisfecho" value="Nada satisfecho">
                                <label class="form-check-label"
                                    for="integracion_en_el_entorno_laboral_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="integracion_en_el_entorno_laboral"
                                    id="integracion_en_el_entorno_laboral_poco_satisfecho" value="Poco satisfecho">
                                <label class="form-check-label"
                                    for="integracion_en_el_entorno_laboral_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="integracion_en_el_entorno_laboral"
                                    id="integracion_en_el_entorno_laboral_satisfecho" value="Satisfecho">
                                <label class="form-check-label" for="integracion_en_el_entorno_laboral_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="integracion_en_el_entorno_laboral"
                                    id="integracion_en_el_entorno_laboral_muy_satisfecho" value="Muy satisfecho">
                                <label class="form-check-label"
                                    for="integracion_en_el_entorno_laboral_muy_satisfecho">
                                    Muy satisfecho
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="observaciones"><strong>Observaciones.</strong></label>
                            <textarea class="form-control" name="observaciones" id="observaciones" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="sugerencias_de_mejora"><strong>Sugerencias generales de mejora.</strong></label>
                            <textarea class="form-control" name="sugerencias_de_mejora" id="sugerencias_de_mejora" rows="3"></textarea>
                        </div>

                        <div>
                            <p><strong>Valoración general de las prácticas.</strong></p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="valoracion_general_de_las_practicas"
                                    id="valoracion_general_de_las_practicas_nada_satisfecho" value="Nada satisfecho">
                                <label class="form-check-label"
                                    for="valoracion_general_de_las_practicas_nada_satisfecho">
                                    Nada satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="valoracion_general_de_las_practicas"
                                    id="valoracion_general_de_las_practicas_poco_satisfecho" value="Poco satisfecho">
                                <label class="form-check-label"
                                    for="valoracion_general_de_las_practicas_poco_satisfecho">
                                    Poco satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="valoracion_general_de_las_practicas"
                                    id="valoracion_general_de_las_practicas_satisfecho" value="Satisfecho">
                                <label class="form-check-label" for="valoracion_general_de_las_practicas_satisfecho">
                                    Satisfecho
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                    name="valoracion_general_de_las_practicas" id="valoracion_general_de_las_practicas"
                                    value="Muy satisfecho">
                                <label class="form-check-label"
                                    for="valoracion_general_de_las_practicas_muy_satisfecho">
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
