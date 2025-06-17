@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Asignar Profesores al Curso Académico <span class="fw-bold">{{ $course->years }}</span></h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('curso_academico.storeTeachers', $course->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            @foreach($teachers as $profesor)
                                <div class="form-check mb-2">
                                    <input 
                                        type="checkbox" 
                                        class="form-check-input" 
                                        id="profesor_{{ $profesor->id }}" 
                                        name="profesores[]" 
                                        value="{{ $profesor->id }}" 
                                        {{ $profesor->estado_profesor === 'Definitivo' ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="profesor_{{ $profesor->id }}">
                                        {{ $profesor->nombre }} {{ $profesor->apellido }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <a href="{{ route('cursos.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection