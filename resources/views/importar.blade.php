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
            @isset($cursos)
                <div class="form-group">
                    <label for="curso">Seleccione el curso al que pertenecen los alumnos que se van a importar.</label>
                    <select name="curso" id="curso" required style="width: 100%">
                        @foreach ($cursos as $curso)
                            <option value="{{ $curso->id }}">
                                {{ $curso->curso . 'º ' . $curso->grupo . ' ' . $curso->ciclo . ' ' . $curso->turno }}</option>
                        @endforeach
                    </select>
                </div>
            @endisset
            @isset($convocatorias)
            <div class="form-group">
                <label for="convocatoria">Seleccione la convocatoria de los alumnos que se van a importar.</label>
                <select name="convocatoria" id="convocatoria" required style="width: 100%">
                    @foreach ($convocatorias as $convocatoria)
                    <option value="{{ $convocatoria->id }}">
                        {{ $convocatoria->periodo . '   '. $convocatoria->anno_academico }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endisset
            <div class="form-group">
                <label for="archivo">Seleccionar archivo CSV</label>
                <input type="file" name="archivo" id="archivo" accept=".csv" required>
            </div>
            <button type="submit" class="btn btn-primary">Importar</button>
        </form>
    </div>

    {{-- Select2 --}}
    <script>
        $(document).ready(function() {
            $('#curso').select2({
                language: {
                    noResults: function() {
                        return 'No se encontraron resultados';
                    }
                }
            });
        });
    </script>

    @if ($actionImagenes != null)
        <div class="container mt-4">
            <h1>Subir imágenes</h1>
            <form action="{{ $actionImagenes }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="archivo">Seleccionar imágenes</label>
                    <input type="file" name="archivo[]" id="archivo" accept="image/*" multiple required>
                </div>
                <button type="submit" class="btn btn-primary">Subir</button>
            </form>
        </div>
    @endif
@endsection
