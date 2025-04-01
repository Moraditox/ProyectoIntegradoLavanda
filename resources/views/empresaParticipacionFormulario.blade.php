<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lavanda - {{ $empresa->nombre }} - Participación en Convocatoria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="{{ asset('storage/images/favicon.ico') }}">
</head>

<body>
    <div class="text-center">
        <img src="{{ asset('storage/avatar/iesgrancapitan.png') }}" alt="Logo" style="max-width: 150px;">
        <h1>Lavanda</h1>
        <h1>Formulario de Participación en Convocatoria para empresa {{$empresa->nombre}}</h1>
    </div>

    <div class="card card-default">
        <div class="card-body">
            <form method="POST"
                action="{{ route('empresa.participacion.procesar', ['token' => $empresa->token, 'convocatoria_id' => $convocatoria->id]) }}"
                role="form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="convocatoria_id" value="{{ $convocatoria->id }}">
            
                <div class="form-group">
                    @foreach($convocatoria->cursosAcademicos as $cursoAcademico)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ciclo[{{ $cursoAcademico->ciclos->id }}]"
                            value="{{ $cursoAcademico->ciclos->id }}" id="curso{{ $cursoAcademico->ciclos->id }}">
                        <label class="form-check-label" for="curso{{ $cursoAcademico->ciclos->id }}">
                            {{ $cursoAcademico->ciclos->ciclo }}
                        </label>
                        <input type="number" name="numero_plazas[{{ $cursoAcademico->ciclos->id }}]"
                            id="numero_plazas_{{ $cursoAcademico->ciclos->id }}" class="form-control"
                            placeholder="Número de plazas para {{ $cursoAcademico->ciclos->ciclo }}">
                        <label for="perfil_{{ $cursoAcademico->ciclos->id }}"><strong>Perfil Requerido:</strong></label>
                        <textarea class="form-control" name="perfil[{{ $cursoAcademico->ciclos->id }}]"
                            id="perfil_{{ $cursoAcademico->ciclos->id }}" rows="3"
                            placeholder="Describa el perfil requerido para las plazas"></textarea>
                        <label for="tareas_{{ $cursoAcademico->ciclos->id }}"><strong>Tareas a Realizar:</strong></label>
                        <textarea class="form-control" name="tareas[{{ $cursoAcademico->ciclos->id }}]"
                            id="tareas_{{ $cursoAcademico->ciclos->id }}" rows="3"
                            placeholder="Detalle las tareas a realizar en las plazas ofrecidas"></textarea>
                    </div>
                    @endforeach
                </div>
            
                <div class="form-group">
                    <label for="observaciones"><strong>Observaciones:</strong></label>
                    <textarea class="form-control" name="observaciones" id="observaciones" rows="3"
                        placeholder="Cualquier otra observación relevante"></textarea>
                </div>
            
                <div class="box-footer mt-2">
                    <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
                    <a href="/lavanda/public/" class="btn btn-danger">{{ __('Cancelar') }}</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>