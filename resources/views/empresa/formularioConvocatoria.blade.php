<div class="form-row">
    <div class="form-group col-md-2">
        <label for="convocatoria_id">{{ __('Convocatoria') }}</label>
        <input type="text" class="form-control" value="{{ $convocatorias[0]->periodo }} - {{ $convocatorias[0]->anno_academico }}" disabled>
        <input type="hidden" name="convocatoria_id" value="{{ $convocatorias[0]->id }}">
    </div>
    <div class="form-group col-md-3">
        <label for="profesor_referencia_id">{{ __('Profesor de Referencia') }}</label>
        <select name="profesor_referencia_id" id="profesor_referencia_id" class="form-control select2">
            <option value="">{{ __('Sin profesor seleccionado') }}</option>
            @foreach($profesores as $profesor)
                <option value="{{ $profesor->id }}" 
                    {{ old('profesor_referencia_id', isset($empresa) ? $empresa->profesorId : null) == $profesor->id ? 'selected' : '' }}>
                    {{ $profesor->nombre }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="alumno_referencia_id">{{ __('Alumno de Referencia') }}</label>
        <select name="alumno_referencia_id" id="alumno_referencia_id" class="form-control select2">
            <option value="">{{ __('Sin alumno seleccionado') }}</option>
            @foreach($alumnos as $alumno)
                <option value="{{ $alumno->id }}" 
                    {{ old('alumno_referencia_id', isset($empresa) ? $empresa->alumnoId : null) == $alumno->id ? 'selected' : '' }}>
                    {{ $alumno->nombre . ' ' . $alumno->apellido1 . ' ' . $alumno->apellido2 }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">
        <label for="observaciones">{{ __('Observaciones') }}</label>
        <textarea name="observaciones" id="observaciones" class="form-control" rows="1" style="resize: none; min-height: 38px; max-height: 38px;">{{ old('observaciones', isset($empresa) ? $empresa->observaciones : '') }}</textarea>
    </div>
</div>

<div class="form-group">
    <label>{{ __('Asignar plazas') }}</label>
    <button type="button" class="btn btn-success mb-1" id="addRow">
        <i class="fas fa-plus"></i>
    </button>
    <table class="table table-bordered" id="gradosTable">
        <thead>
            <tr>
                <th>{{ __('Especialidad') }}</th>
                <th>{{ __('Número de Plazas') }}</th>
                <th>{{ __('Perfil') }}</th>
                <th>{{ __('Tareas') }}</th>
                <th>{{ __('Observaciones') }}</th>
                <th>{{ __('Acciones') }}</th>
            </tr>
        </thead>
        <tbody>
            @if(old('especialidades'))
                @foreach(old('especialidades') as $i => $esp)
                    <tr>
                        <td>
                            <select name="especialidades[{{ $i }}][nombre]" class="form-control">
                                @foreach($especialidades as $especialidad)
                                    <option value="{{ $especialidad }}" {{ $esp['nombre'] == $especialidad ? 'selected' : '' }}>
                                        {{ $especialidad }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="especialidades[{{ $i }}][plazas]" class="form-control" value="{{ $esp['plazas'] }}" min="1" required>
                        </td>
                        <td>
                            <input type="text" name="especialidades[{{ $i }}][perfil]" class="form-control" value="{{ $esp['perfil'] }}" required>
                        </td>
                        <td>
                            <input type="text" name="especialidades[{{ $i }}][tareas]" class="form-control" value="{{ $esp['tareas'] }}" required>
                        </td>
                        <td>
                            <input type="text" name="especialidades[{{ $i }}][observaciones]" class="form-control" value="{{ $esp['observaciones'] }}" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-row" title="{{ __('Eliminar esta fila de la tabla') }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @elseif(isset($plazas) && count($plazas) > 0)
                @foreach($plazas as $i => $esp)
                    <tr>
                        <td>
                            <select name="especialidades[{{ $i }}][nombre]" class="form-control">
                                @foreach($especialidades as $especialidad)
                                    <option value="{{ $especialidad }}" {{ $esp->especialidad == $especialidad ? 'selected' : '' }}>
                                        {{ $especialidad }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="especialidades[{{ $i }}][plazas]" class="form-control" value="{{ $esp->plazas }}" min="1" required>
                        </td>
                        <td>
                            <input type="text" name="especialidades[{{ $i }}][perfil]" class="form-control" value="{{ $esp->perfil }}" required>
                        </td>
                        <td>
                            <input type="text" name="especialidades[{{ $i }}][tareas]" class="form-control" value="{{ $esp->tareas }}" required>
                        </td>
                        <td>
                            <input type="text" name="especialidades[{{ $i }}][observaciones]" class="form-control" value="{{ $esp->observaciones }}" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-row" title="{{ __('Eliminar esta fila de la tabla') }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>