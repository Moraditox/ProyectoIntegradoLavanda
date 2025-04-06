@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="me-3">Lista de Cursos Académicos</h1>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createYearModal">
            Crear año academico
        </button>

        <!-- Modal -->
        <div class="modal fade" id="createYearModal" tabindex="-1" aria-labelledby="createYearModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="createYearModalLabel">Crear Año Académico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('curso_academico.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                    <label for="yearInput" class="form-label">Año Académico</label>
                    <input type="text" class="form-control" id="yearInput" name="anno" placeholder="Ejemplo: 2023-2024" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </div>
                </form>
            </div>
            </div>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Año</th>
                <th>Acciones</th>
                <th>Profesores</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $curso)
            <tr>
                <td>{{ $curso->id }}</td>
                <td>{{ $curso->years }}</td>
                <td>
                    CRUD A FUTURO
                    {{-- <a href="{{ route('curso_academico.show', $curso->id) }}" class="btn btn-info btn-sm">Ver</a>
                    {{-- CRUD A FUTURO --}}
                    {{-- <a href="{{ route('curso_academico.show', $curso->id) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('curso_academico.edit', $curso->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('curso_academico.destroy', $curso->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este curso?')">Eliminar</button>
                    </form> --}}
                </td>
                <td>
                    <a href="{{ route('cursos.assingTeachers', $curso->id) }}" class="btn btn-primary btn-sm">Asignar Profesores</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection