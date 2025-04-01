@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @foreach ($alumnos as $alumno)
                <div class="col-md-3">
                    <div class="card mb-4 text-center">
                        <img src="{{ asset('storage/alumnado/perfil/' . $alumno->imagen) }}" class="card-img-top"
                            alt="Imagen del alumno">
                        <div class="card-body">
                            <h5 class="card-title">{{ $alumno->nombre }} {{ $alumno->apellido1 }} {{ $alumno->apellido2 }}</h5>
                            <p class="card-text"><strong>Email:</strong> <a
                                    href="mailto:{{ $alumno->email_corporativo }}">{{ $alumno->email_corporativo }}</a></p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('alumnos.infoAlumno', $alumno->id) }}" class="btn btn-primary">Más información</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
