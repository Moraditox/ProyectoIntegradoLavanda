@extends('layouts.app')

@section('content')
    <div class="container">
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif

    @if ($duplicados = Session::get('duplicados'))
    <div class="alert alert-warning">
        <p>Alumnos duplicados: {{ implode(', ', $duplicados) }}</p>
    </div>
    @endif

        @yield('titulo')
        <p>Antes de importar los datos, asegúrate de que el archivo CSV cumpla con los siguientes requisitos:</p>
        <ol>
            <li>El archivo debe estar en formato CSV.</li>
            <li>Debe de contener los siguientes campos:</li>
            @yield('requisitos')

        </ol>
        <form action="{{ $actionImportar }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                @isset($cursos)
                    <div class="form-group col-md-6">
                        <label for="curso">Seleccione el curso al que pertenecen los alumnos que se van a importar.</label>
                        <select name="curso" id="curso" class="form-control" required>
                            @foreach ($cursos as $curso)
                                <option value="{{ $curso->nombre }}">
                                    {{ $curso->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endisset
                <div class="form-group col-md-6">
                    <label for="convocatoria">La convocatoria en preparación actual</label>
                    @if (isset($convocatoria) && $convocatoria)
                        <input type="text" class="form-control" value="{{ $convocatoria->periodo }}" disabled>
                        <input type="hidden" name="convocatoria" value="{{ $convocatoria->id }}">
                    @else
                        <input type="text" class="form-control" value="No existe convocatoria en preparación" disabled>
                    @endif
                </div>
            </div>
            @if (!request()->is('profesorado*'))
            <div class="form-group">
                <label for="anno_academico">Seleccione el año académico</label>
                <select name="anno_academico" id="anno_academico" class="form-control" required>
                    @foreach ($annoAcademico as $anno)
                        <option value="{{ $anno["id"] }}" {{ (isset($annoActual) && $anno["years"] == $annoActual) ? 'selected' : '' }}>
                            {{ $anno["years"] }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="form-group">
                <label for="archivo">Seleccionar archivo CSV</label>
                <input type="file" name="archivo" id="archivo" accept=".csv" required>
            </div>
            <button type="submit" class="btn btn-primary">Importar</button>
        </form>
    </div>
@endsection