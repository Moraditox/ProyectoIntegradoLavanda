<div class="box box-info padding-1">
    <div class="box-body">

        <div class="form-group">
            {{ Form::label('periodo') }}
            {{ Form::text('periodo', $convocatoria->periodo, ['class' => 'form-control' . ($errors->has('periodo') ? ' is-invalid' : ''), 'placeholder' => 'Periodo']) }}
            {!! $errors->first('periodo', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('fecha_inicio') }}
            {{ Form::date('fecha_inicio', $convocatoria->fecha_inicio, ['class' => 'form-control' . ($errors->has('fecha_inicio') ? ' is-invalid' : '')]) }}
            {!! $errors->first('fecha_inicio', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('fecha_fin') }}
            {{ Form::date('fecha_fin', $convocatoria->fecha_fin, ['class' => 'form-control' . ($errors->has('fecha_fin') ? ' is-invalid' : '')]) }}
            {!! $errors->first('fecha_fin', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('observaciones') }}
            {{ Form::text('observaciones', $convocatoria->observaciones, ['class' => 'form-control' . ($errors->has('observaciones') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones']) }}
            {!! $errors->first('observaciones', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('anno_academico', 'Año Académico') }}
            <select class="form-control select2" name="anno_academico">
                @if ($convocatoria->anno_academico)
                    <option value="{{ $convocatoria->anno_academico }}" selected>{{ $convocatoria->anno_academico }}</option>
                @else
                    <option value="" selected disabled>Selecciona un año académico</option>
                    @foreach ($annos as $annoValue => $annoLabel)
                        <option value="{{ $annoValue }}">{{ $annoLabel }}</option>
                    @endforeach
                @endif
            </select>
            {!! $errors->first('anno_academico', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('curso_academico[]', 'Cursos Académicos', ['class' => 'mb-2']) }}
            <div class="form-group d-flex flex-row flex-wrap justify-content-between align-items-center">
                <select class="form-control select2" name="curso_academico[]" multiple>
                    <option value="" disabled>Selecciona los cursos académicos</option>
                    {{-- SUGERENCIA: AÑADIR TODOS LOS CURSOS DEL AÑO ACADÉMICO, HACE FALTA HACER CAMBIOS PREVIOS PARA HACER ESTO
                    CONSULTAR A JESÚS --}}
                    @foreach ($cursos as $cursoValue => $cursoLabel)
                        @php
                        if(isset($cursosSeleccionados)) {
                            $isSelected = in_array($cursoValue, $cursosSeleccionados); // Verificar si el curso está seleccionado previamente
                        }
                        @endphp
                        @if (isset($isSelected))
                            <option value="{{ $cursoValue }}" {{ $isSelected ? 'selected' : '' }}>{{ $cursoLabel }}</option>
                        @else
                            <option value="{{ $cursoValue }}">{{ $cursoLabel }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            {!! $errors->first('curso_academico', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        {{-- Este es el campo para añadir empresas --}}
        {{-- <div class="form-group">
            {{ Form::label('empresas[]', 'Empresas', ['class' => 'mb-2']) }}
            <div class="form-group d-flex flex-row flex-wrap justify-content-between align-items-center">
                <select class="form-control select2" name="empresas[]" multiple>
                    <option value="" disabled>Selecciona las empresas</option>
                    @foreach ($empresas as $empresaValue => $empresaLabel)
                        @php
                        if (isset($empresasSeleccionadas)) {
                            $isSelected = in_array($empresaValue, $empresasSeleccionadas); // Verificar si la empresa está seleccionada previamente
                        }
                        @endphp
                        @if (isset($isSelected)) {
                            <option value="{{ $empresaValue }}" {{ $isSelected ? 'selected' : '' }}>{{ $empresaLabel }}</option>
                        }
                        @else
                            <option value="{{ $empresaValue }}">{{ $empresaLabel }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            {!! $errors->first('empresas', '<div class="invalid-feedback">:message</div>') !!}
        </div> --}}
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        }
                    }
                });
            });
        </script>
    </div>
    <div class="box-footer mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
        <a href="{{ route('convocatorias.index') }}" class="btn btn-danger">{{ __('Cancelar') }}</a>
    </div>
</div>
