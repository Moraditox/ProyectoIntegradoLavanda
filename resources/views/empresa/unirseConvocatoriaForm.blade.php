@extends('layouts.app')

{{-- Este formulario permite a una empresa unirse a una convocatoria --}}
@section('content')

    <div class="card">
        <div class="card-header">
            <h2>{{ __('Unirse a la Convocatoria') }}</h2>
            <h3>{{ $empresa->nombre }}</h3>
        </div>
        <div class="card-body">
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

                <div class="form-group">
                    <label for="alumno_referencia">{{ __('Alumno de Referencia') }}</label>
                    <select name="alumno_referencia_id" id="alumno_referencia_id" class="form-control">
                        <option value="">{{ __('Sin alumno seleccionado') }}</option>
                        @foreach($alumnos as $alumno)
                            <option value="{{ $alumno->id }}">
                                {{ $alumno->nombre . ' ' . $alumno->apellido1 . ' ' . $alumno->apellido2 }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="profesor_referencia">{{ __('Profesor de Referencia') }}</label>
                    <select name="profesor_referencia_id" id="profesor_referencia_id" class="form-control">
                        <option value="">{{ __('Sin profesor seleccionado') }}</option>
                        @foreach($profesores as $profesor)
                            <option value="{{ $profesor->id }}">
                                {{ $profesor->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="observaciones">{{ __('Observaciones') }}</label>
                    <textarea name="observaciones" id="observaciones" class="form-control" rows="3">{{ old('observaciones') }}</textarea>
                </div>

                <div class="form-group">
                    <label>{{ __('Asignar plazas') }}</label>
                    <table class="table table-bordered" id="gradosTable">
                        <thead>
                            <tr>
                                <th>{{ __('Especialidad') }}</th>
                                <th>{{ __('Número de Plazas') }}</th>
                                <th>{{ __('Observaciones') }}</th>
                                <th>{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="especialidades[0][nombre]" class="form-control">
                                        <option value="1 DEV">1 DEV</option>
                                        <option value="2 DEV">2 DEV</option>
                                        <option value="1 ASIR">1 ASIR</option>
                                        <option value="2 ASIR">2 ASIR</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="especialidades[0][plazas]" class="form-control" placeholder="{{ __('Número de Plazas') }}" min="1" required>
                                </td>
                                <td>
                                    <input type="text" name="especialidades[0][observaciones]" class="form-control" placeholder="{{ __('Observaciones') }}" required>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger remove-row">{{ __('Eliminar') }}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-success" id="addRow">{{ __('Añadir Fila') }}</button>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const table = document.getElementById('gradosTable').getElementsByTagName('tbody')[0];
                        const addRowButton = document.getElementById('addRow');
                        let rowIndex = 1; // Índice inicial para las filas

                        addRowButton.addEventListener('click', function () {
                            const newRow = table.insertRow();
                            newRow.innerHTML = `
                                <td>
                                    <select name="especialidades[${rowIndex}][nombre]" class="form-control">
                                        <option value="1 DEV">1 DEV</option>
                                        <option value="2 DEV">2 DEV</option>
                                        <option value="1 ASIR">1 ASIR</option>
                                        <option value="2 ASIR">2 ASIR</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="especialidades[${rowIndex}][plazas]" class="form-control" placeholder="{{ __('Número de Plazas') }}" min="1" required>
                                </td>
                                <td>
                                    <input type="text" name="especialidades[${rowIndex}][observaciones]" class="form-control" placeholder="{{ __('Observaciones') }}" required>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger remove-row">{{ __('Eliminar') }}</button>
                                </td>
                            `;
                            rowIndex++; // Incrementar el índice para la siguiente fila
                        });

                        table.addEventListener('click', function (event) {
                            if (event.target.classList.contains('remove-row')) {
                                const row = event.target.closest('tr');
                                row.remove();
                            }
                        });
                    });
                </script>

                <button type="submit" class="btn btn-primary">{{ __('Enviar Solicitud') }}</button>
            </form>
        </div>
    </div>

@endsection