@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Asignar Profesores al Curso Académico {{ $course->years }}</h1>
    <form action="{{ route('curso_academico.storeTeachers', $course->id) }}" method="POST">
        @csrf
        @foreach($teachers as $profesor)
            <div class="form-check">
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
        <button type="submit" class="btn btn-primary mt-3">Guardar</button>
    </form>
</div>
@endsection