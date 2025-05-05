@extends('layouts.app')

{{-- Este formulario permite a una empresa unirse a una convocatoria --}}
@section('content')

    <h2>{{ __('Unirse a la Convocatoria') }}</h2>
    <h3>{{ $empresa->nombre }}</h3>

    <form method="POST" action="{{ route('empresa.unirseConvocatoria', $empresa->id) }}" role="form" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="mensaje">{{ __('Convocatoria') }}</label>
            <select name="convocatoria_id" id="convocatoria_id" class="form-control">
                @foreach($convocatorias as $convocatoria)
                    <option value="{{ $convocatoria->id }}">
                        {{ $convocatoria->periodo }} - {{ $convocatoria->anno_academico }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Este bloque indica quien ha conseguido el contacto con la empresa
        De momento NO ESTÁ IMPLEMENTADO PARA SER GUARDADO, Y HAY QUE ENSEÑAR SOLO LOS ALUMNOS Y PROFESORES DEL AÑO ACTUAL --}}
        <div class="form-group">
            <label for="referente">{{ __('Referente de Asignación') }}</label>
            <select name="referente_id" id="referente_id" class="form-control">
                @foreach($alumnos as $alumno)
                    <option value="{{ $alumno->id }}">
                        {{ 'alumno - ' . $alumno->nombre . ' ' . $alumno->apellido1 . ' ' . $alumno->apellido2 }}
                    </option>
                @endforeach

                @foreach($profesores as $profesor)
                    <option value="{{ $profesor->id }}">
                        {{ 'profesor - ' . $profesor->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="tareas_a_realizar">{{ __('Tareas a Realizar') }}</label>
            <textarea name="tareas_a_realizar" id="tareas_a_realizar" class="form-control" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="perfil_requerido">{{ __('Perfil Requerido') }}</label>
            <textarea name="perfil_requerido" id="perfil_requerido" class="form-control" rows="4"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('Enviar Solicitud') }}</button>
    </form>

@endsection