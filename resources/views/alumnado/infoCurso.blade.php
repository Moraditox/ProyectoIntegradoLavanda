@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h3>Alumnos matriculados en {{ $curso->curso . 'º ' . $curso->grupo . ' ' . $curso->ciclo . ' ' . $curso->turno }} - {{ $anno->anno }}</h3>
            </div>
            <div class="row">
                @foreach ($alumnos as $alumno)
                    <div style="margin-bottom:20px" class="col-md-3">
                        <div style="height:100%" class="card mb-4 text-center">
                            @if ($alumno->imagen && file_exists(storage_path('app/public/alumnado/perfil/' . $alumno->imagen)))
                                <img src="{{ asset('storage/alumnado/perfil/' . $alumno->imagen) }}" class="card-img-top" alt="Imagen del alumno">
                            @else
                                <img  src="{{ asset('storage/avatar/avatar2.png') }}" class="card-img-top" alt="Avatar predeterminado">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $alumno->nombre }} {{ $alumno->apellido1 }} {{ $alumno->apellido2 }}</h5>
                                <p class="card-text"><strong>Email:</strong> <a href="mailto:{{ $alumno->email_corporativo }}">{{ $alumno->email_corporativo }}</a></p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('alumnos.infoAlumno', $alumno->id) }}" class="btn btn-primary">Más información</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
