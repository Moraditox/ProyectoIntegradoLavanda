<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-4">
                {{ Form::label('periodo') }}
                {{ Form::text('periodo', $convocatoria->periodo, ['class' => 'form-control' . ($errors->has('periodo') ? ' is-invalid' : ''), 'placeholder' => 'Periodo']) }}
                {!! $errors->first('periodo', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            <div class="form-group col-md-4">
                {{ Form::label('fecha_inicio') }}
                {{ Form::date('fecha_inicio', $convocatoria->fecha_inicio, ['class' => 'form-control' . ($errors->has('fecha_inicio') ? ' is-invalid' : '')]) }}
                {!! $errors->first('fecha_inicio', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            <div class="form-group col-md-4">
                {{ Form::label('fecha_fin') }}
                {{ Form::date('fecha_fin', $convocatoria->fecha_fin, ['class' => 'form-control' . ($errors->has('fecha_fin') ? ' is-invalid' : '')]) }}
                {!! $errors->first('fecha_fin', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
            {{ Form::label('observaciones') }}
            {{ Form::text('observaciones', $convocatoria->observaciones, ['class' => 'form-control' . ($errors->has('observaciones') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones']) }}
            {!! $errors->first('observaciones', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            <div class="form-group col-md-4">
            {{ Form::label('anno_academico', 'Año Académico') }}
            @if(request()->is('convocatorias/create'))
                <select name="anno_academico" class="form-control{{ $errors->has('anno_academico') ? ' is-invalid' : '' }}">
                    <option value="" disabled {{ empty(old('anno_academico', $convocatoria->anno_academico)) ? 'selected' : '' }}>Selecciona el año académico</option>
                    @foreach ($annosAcademicos as $anno)
                        <option value="{{ is_object($anno) ? $anno->id : $anno['id'] }}"
                            @if (
                                (isset($annoAcademicoActual) && ((is_object($anno) ? $anno->years : $anno['years']) == $annoAcademicoActual["years"])) ||
                                (old('anno_academico', $convocatoria->anno_academico) == (is_object($anno) ? $anno->id : $anno['id']))
                            )
                            selected
                            @endif
                        >
                            {{ is_object($anno) ? $anno->years : $anno['years'] }}
                        </option>
                    @endforeach
                </select>
            @else
                <input type="hidden" name="anno_academico" value="{{ $annosAcademicos->id }}">
                <input type="text" class="form-control{{ $errors->has('anno_academico') ? ' is-invalid' : '' }}" value="{{ $annosAcademicos->years }}" readonly>
            @endif
            {!! $errors->first('anno_academico', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            <div class="form-group col-md-4">
            {{ Form::label('curso_academico[]', 'Cursos Académicos', ['class' => 'mb-2']) }}
            <select class="form-control select2" id="curso_academico" name="curso_academico[]" multiple>
                <option value="" disabled>Selecciona los cursos académicos</option>
                @php
                $selectedAnno = old('anno_academico', $convocatoria->anno_academico);
                $cursosMostrar = [];
                if (!empty($selectedAnno) && isset($annosAcademicosCursos[$selectedAnno])) {
                    $cursosMostrar = $annosAcademicosCursos[$selectedAnno];
                }
                @endphp
                @foreach ($cursosMostrar as $curso)
                @if (isset($cursosSeleccionados))
                    <option value="{{ $curso }}" {{ in_array($curso, $cursosSeleccionados) ? 'selected' : '' }}>{{ $curso }}</option>
                @else
                    <option value="{{ $curso }}">{{ $curso }}</option>
                @endif
                @endforeach
            </select>
            {!! $errors->first('curso_academico', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            @if (request()->is('convocatorias/create'))
            <script>
            const annosAcademicosCursos = @json($annosAcademicosCursos);
            $(document).ready(function() {
                function actualizarCursos() {
                    const annoId = $('select[name="anno_academico"]').val();
                    const cursos = annosAcademicosCursos[annoId] || [];
                    const $cursoSelect = $('#curso_academico');
                    $cursoSelect.empty();
                    if (cursos.length) {
                        cursos.forEach(function(curso) {
                            $cursoSelect.append(
                                $('<option>', { value: curso, text: curso })
                            );
                        });
                    } else {
                        $cursoSelect.append(
                            $('<option>', { value: '', text: 'No hay cursos disponibles', disabled: true })
                        );
                    }
                    $cursoSelect.trigger('change');
                }

                // Llenar cursos al cargar la página
                actualizarCursos();

                // Actualizar cursos al cambiar año académico
                $('select[name="anno_academico"]').on('change', actualizarCursos);
            });
            </script>
            @endif
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                {{ Form::label('estado', 'Estado') }}
                <select class="form-control select2" name="estado">
                    <option value="" disabled {{ empty(old('estado', $convocatoria->estado)) ? 'selected' : '' }}>Selecciona el estado</option>
                    <option value="Activa" {{ (old('estado', $convocatoria->estado) == 'Activa') ? 'selected' : '' }}>Activa</option>
                    <option value="Preparación" {{ (old('estado', $convocatoria->estado) == 'Preparación' || (empty(old('estado', $convocatoria->estado)) && !isset($convocatoria->estado))) ? 'selected' : '' }}>Preparación</option>
                    <option value="Terminada" {{ (old('estado', $convocatoria->estado) == 'Terminada') ? 'selected' : '' }}>Terminada</option>
                </select>
                {!! $errors->first('estado', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            {{-- Puedes añadir más campos aquí si lo necesitas para completar la fila de 3 --}}
        </div>
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
        <a href="{{ url()->previous() }}" class="btn btn-danger">{{ __('Cancelar') }}</a>
    </div>
</div>
