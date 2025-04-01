<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lavanda - {{ $empresa->nombre }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('storage/images/favicon.ico') }}">
</head>

<body>
    <div class="text-center">
        <!-- <img src={{ asset('storage/images/Lavanda.png') }} alt="Logo Lavanda" style="width: 100px; height: 100px;"> -->
        <h1>Lavanda</h1>
    </div>
    <div class="card">
        <div class="card-header text-center">
            Listado de alumnos
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success m-2">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card-body d-flex justify-content-around flex-wrap">
            @if (count($alumnos) == 0)
                <div class="alert alert-danger" role="alert">
                    Aún no hay alumnos asignados a esta empresa.
                </div>
            @endif
            @foreach ($alumnos as $alumno)
                <div class="col-md-3">
                    <div style="display: flex; justify-content: center; align-items: center;" class="card mb-4 text-center">
                        @if ($alumno['imagen'] && file_exists(storage_path('app/public/alumnado/perfil/' . $alumno['imagen'])))
                        <img src="{{ asset('storage/alumnado/perfil/' . $alumno['imagen']) }}" alt="Imagen del Alumno" class="rounded-circle"
                            style="width: 200px; height: 200px; object-fit: cover;">
                        @else
                        <img src="{{ asset('storage/avatar/avatar2.png') }}" alt="Avatar predeterminado" class="rounded-circle"
                            style="width: 200px; height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $alumno->nombre }} {{ $alumno->apellido1 }}
                                {{ $alumno->apellido2 }}</h5>
                            <p class="card-text"><strong>Email:</strong> <a
                                    href="mailto:{{ $alumno->email_corporativo }}">{{ $alumno->email_corporativo }}</a>
                            </p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('informeEmpresaAlumno', ['token' => $empresa->token, 'alumno' => $alumno->id]) }}"
                                class="btn btn-primary">Rellenar Formulario</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card-footer"></div>
    </div class="card">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
