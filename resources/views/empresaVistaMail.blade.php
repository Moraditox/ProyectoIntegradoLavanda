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
        <img src={{ asset('storage/logos/Lavanda.png') }} alt="Logo Lavanda" style="width: 100px; height: 100px;">
        <h1>Lavanda</h1>
    </div>
    <img src="{{ asset('storage/avatar/iesgrancapitan.png') }}" alt="Logo" style="max-width: 100px;">
    @if ($message = Session::get('success'))
        <div class="alert alert-success m-2">
            <p>{{ $message }}</p>
        </div>
        <div class="text-center">
    @endif
    </div>
    @if (!$participa)
        <h2>{{ $empresa->nombre }} bienvenido a la página de registro de empresas en las convocatorias del IES Gran Capitán.</h2>
        <code>Para registrarse en la convocatoria, por favor rellena los campos a continuación: </code>
        <div class="card card-default">
            <div class="card-body">
                <form method="GET" action="{{ route('unirseConvocatoria', ['token' => $empresa->token]) }}"
                    role="form" enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="numero_alumnos">Número de Alumnos que solicita la empresa:</label>
                                <input type="number" name="numero_alumnos" id="numero_alumnos"
                                    class="form-control{{ $errors->has('numero_alumnos') ? ' is-invalid' : '' }}"
                                    placeholder="Introduzca el Número de alumnos que solicita">
                                {!! $errors->first('numero_alumnos', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                            <div class="form-group">
                                <label for="tareas_a_realizar">¿Qué tareas van a realizar los alumnos que acudan a la
                                    empresa?</label>
                                <textarea name="tareas_a_realizar" id="tareas_a_realizar"
                                    class="form-control{{ $errors->has('tareas_a_realizar') ? ' is-invalid' : '' }}"
                                    placeholder="Introduzca las tareas que realizarán los alumnos" rows="5"
                                    style="resize: vertical; width: 100%;"></textarea>
                                {!! $errors->first('tareas_a_realizar', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                            <div class="form-group">
                                <label for="perfil_requerido">¿Qué perfil de alumno está buscando tu empresa?</label>
                                <textarea name="perfil_requerido" id="perfil_requerido"
                                    class="form-control{{ $errors->has('perfil_requerido') ? ' is-invalid' : '' }}"
                                    placeholder="Introduzca el perfil de alumno que solicita" rows="3" style="resize: vertical; width: 100%;"></textarea>
                                {!! $errors->first('perfil_requerido', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                        <div class="box-footer mt-2">
                            <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
                            <a href="/lavanda/public/" class="btn btn-danger">{{ __('Cancelar') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @else
        <h2 class="text-center">Ya participas en la convocatoria</h2>
    @endif
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
