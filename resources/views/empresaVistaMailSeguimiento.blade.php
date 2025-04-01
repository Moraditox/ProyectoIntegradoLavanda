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
        <!-- <img src={{ asset('storage/logos/Lavanda.png') }} alt="Logo Lavanda" style="width: 100px; height: 100px;"> -->
        <h1>Lavanda</h1>
        <img src="https://www.iesgrancapitan.org/wp-content/uploads/sites/2/2021/06/Logo_IES_GranCapitan_header.png"
        alt="Logo IES Gran Capitán" style="width: 10%; height: auto;">
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success m-2">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="card">
        <div class="card-body d-flex justify-content-around">
            <div class="card text-center" style="width: fit-content; height: fit-content;">
                <div class="card-header">Seguimiento Alumnos</div>
                <div class="card-body">
                    <a href="{{ route('alumnosEmpresa', ['token' => $empresa->token]) }}"
                        class="btn btn-primary">Mostrar</a>
                </div>
                <div class="card-footer">
                    Listado de alumnos que estan realizando las prácticas en la empresa.
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
