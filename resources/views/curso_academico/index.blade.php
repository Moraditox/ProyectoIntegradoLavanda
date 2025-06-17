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
                        <input type="text" class="form-control" id="yearInput" name="year" placeholder="Ejemplo: 2023-2024" required>
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

            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Año</th>
                    <th>Profesores</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $curso)
                    <tr>
                        <td>{{ $curso->years }}</td>
                        <td> 
                            @if($curso->profesores->isEmpty())
                                No hay profesores asignados
                                @else
                                    <div class="d-flex flex-wrap align-items-center h-100" style="min-height: 40px;">
                                        @foreach($curso->profesores as $profesor)
                                            {{ $profesor->nombre }} {{ $profesor->apellido1 }} {{ $profesor->apellido2 }}@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                @endif
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