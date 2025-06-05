@extends('layouts.app')

@section('content')
    <div class="container">
        <form class="form-inline d-flex justify-content-center" method="GET" action="{{ route('alumnos.buscar') }}">
            <h4>Buscar alumno:</h4>
            <div class="form-group mx-sm-3 mb-2">
                <label for="nombre" class="sr-only">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="{{ request('nombre') }}">
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label for="apellido1" class="sr-only">Apellido 1:</label>
                <input type="text" class="form-control" id="apellido1" name="apellido1" placeholder="Apellido 1" value="{{ request('apellido1') }}">
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label for="apellido2" class="sr-only">Apellido 2:</label>
                <input type="text" class="form-control" id="apellido2" name="apellido2" placeholder="Apellido 2" value="{{ request('apellido2') }}">
            </div>
            <button type="submit" class="btn btn-primary mb-2">Buscar</button>
        </form>
        @foreach ($annos as $index => $anno)
            <div class="card m-4">
                <div class="card-header text-center d-flex align-items-center justify-content-center" style="cursor:pointer;" onclick="toggleCursos({{ $index }})">
                    <span id="arrow-{{ $index }}" style="transition: transform 0.2s;">&#9654;</span>
                    <span class="ml-2">{{ $anno["years"] }}</span>
                </div>
                <div class="card-body" id="cursos-{{ $index }}" style="display:none;">
                    @foreach ($cursos as $curso)
                        @if (isset($numeroAlumnos[$anno["years"]][$curso["nombre"]]) && $numeroAlumnos[$anno["years"]][$curso["nombre"]] > 0)
                            <div class="card mb-3">
                                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center" style="background: linear-gradient(90deg, #fce4ec 0%, #e3f2fd 100%); border-radius: 8px;">
                                    <div class="d-flex align-items-center mb-2 mb-md-0">
                                        <i class="fas fa-chalkboard-teacher fa-lg text-primary mr-2"></i>
                                        <span class="font-weight-bold" style="font-size: 1.2rem;">{{ $curso["nombre"] }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-pill badge-primary px-3 py-2 mr-3" style="font-size: 1rem;">
                                            {{ $numeroAlumnos[$anno["years"]][$curso["nombre"]] }} alumno(s)
                                        </span>
                                        <a href="{{ url('/alumnos/' . $anno['years'] . '/' . $curso['nombre']) }}" class="btn btn-info btn-sm shadow-sm"><i class="fas fa-users"></i> Ver alumnos</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="card-footer"></div>
            </div>
        @endforeach
    </div>
    <script>
        function toggleCursos(index) {
            var cursos = document.getElementById('cursos-' + index);
            var arrow = document.getElementById('arrow-' + index);
            if (cursos.style.display === 'none') {
                cursos.style.display = 'block';
                arrow.style.transform = 'rotate(90deg)';
            } else {
                cursos.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    </script>
@endsection
