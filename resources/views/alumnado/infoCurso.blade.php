@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">Alumnos matriculados en {{ $curso}} - {{ $anno }}</h3>
                </div>
                <div>
                    <button onclick="window.history.back()" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center" style="font-weight: 500;">
                        <i class="bi bi-arrow-left me-2"></i> Volver
                    </button>
                </div>
            </div>
            <div class="row justify-content-center">
                @foreach ($alumnos as $alumno)
                    <div class="col-md-4 mb-4 d-flex align-items-stretch">
                        <div class="card h-100 shadow border-0 rounded-4 w-100">
                            <div class="card-body d-flex flex-column align-items-center">
                                <div class="mb-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($alumno['nombre'].' '.$alumno['apellido1']) }}&background=6c63ff&color=fff&size=80" alt="Avatar" class="rounded-circle shadow-sm" width="80" height="80">
                                </div>
                                <h5 class="card-title fw-bold text-primary">{{ $alumno["nombre"] }} {{ $alumno["apellido1"] }} {{ $alumno["apellido2"] }}</h5>
                                <p class="card-text text-muted mb-1">
                                    <i class="bi bi-envelope"></i>
                                    <strong>Email:</strong> {{ $alumno["email_corporativo"] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
