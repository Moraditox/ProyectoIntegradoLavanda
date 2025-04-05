@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Cursos Académicos</h1>
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